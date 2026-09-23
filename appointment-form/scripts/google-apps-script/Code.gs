/**
 * Eat Rrite appointments — Google Sheets + Meet web app.
 *
 * Properties: SCRIPT_SECRET, SHEET_ID, TAB_NAME, DISABLED_SLOTS_TAB
 * Services: Google Calendar API
 * Deploy: Web app → Execute as Me → Anyone
 *
 * Actions: list | list_disabled_slots | set_disabled_slot | book | cancel
 */

var HEADERS = [
  "Name",
  "Service",
  "Phone Number",
  "Appointment Date",
  "Appointment Time",
  "Google Meet Link",
  "Appointment Booked At",
];

var DISABLED_HEADERS = ["Date", "Time", "Disabled", "Updated At"];

function doPost(e) {
  try {
    var payload = JSON.parse(e.postData.contents);
    assertSecret_(payload.secret);

    var action = payload.action;
    if (action === "list") {
      return json_({ ok: true, booked: listBooked_(payload) });
    }
    if (action === "list_disabled_slots") {
      return json_({ ok: true, disabled: listDisabled_(payload) });
    }
    if (action === "set_disabled_slot") {
      return json_(setDisabled_(payload));
    }
    if (action === "book") {
      return json_(book_(payload));
    }
    if (action === "cancel") {
      return json_(cancel_(payload));
    }

    return json_({ ok: false, error: "Unknown action." });
  } catch (error) {
    var message = error && error.message ? error.message : String(error);
    var taken = message === "slot_taken";
    return json_({
      ok: false,
      error: taken
        ? "That slot was just booked. Please pick another time."
        : message,
      code: taken ? "slot_taken" : "error",
    });
  }
}

function doGet() {
  return json_({ ok: true, service: "eatrrite-appointments" });
}

function testSheetAccess() {
  Logger.log("Connected: " + appointmentsSheet_({}).getName());
}

/* ---------- sheets ---------- */

function assertSecret_(secret) {
  var expected =
    PropertiesService.getScriptProperties().getProperty("SCRIPT_SECRET") || "";
  if (!expected || secret !== expected) {
    throw new Error("Unauthorized Apps Script request.");
  }
}

function openSpreadsheet_(payload) {
  var props = PropertiesService.getScriptProperties();
  var sheetId =
    (payload && payload.sheet_id) || props.getProperty("SHEET_ID") || "";
  if (!sheetId) {
    throw new Error("Missing SHEET_ID.");
  }
  return SpreadsheetApp.openById(sheetId);
}

function appointmentsSheet_(payload) {
  var props = PropertiesService.getScriptProperties();
  var tab =
    (payload && payload.tab_name) || props.getProperty("TAB_NAME") || "Sheet1";
  var spreadsheet = openSpreadsheet_(payload);
  var sheet = spreadsheet.getSheetByName(tab);
  if (!sheet) {
    sheet = spreadsheet.insertSheet(tab);
  }
  if (sheet.getLastRow() === 0) {
    sheet.appendRow(HEADERS);
    sheet.getRange(1, 1, 1, HEADERS.length).setFontWeight("bold");
    sheet.setFrozenRows(1);
  }
  return sheet;
}

function disabledSheet_(payload, createIfMissing) {
  var props = PropertiesService.getScriptProperties();
  var tab =
    (payload && payload.disabled_tab_name) ||
    props.getProperty("DISABLED_SLOTS_TAB") ||
    "disabled-slots";
  var spreadsheet = openSpreadsheet_(payload);
  var sheet = spreadsheet.getSheetByName(tab);
  if (!sheet) {
    if (!createIfMissing) return null;
    sheet = spreadsheet.insertSheet(tab);
    sheet.appendRow(DISABLED_HEADERS);
    sheet.getRange(1, 1, 1, DISABLED_HEADERS.length).setFontWeight("bold");
    sheet.setFrozenRows(1);
    sheet.getRange("A:B").setNumberFormat("@");
  }
  return sheet;
}

function dataRows_(sheet) {
  var lastRow = sheet.getLastRow();
  if (lastRow < 2) return [];
  return sheet.getRange(2, 1, lastRow - 1, sheet.getLastColumn()).getValues();
}

/* ---------- list ---------- */

function listBooked_(payload) {
  var rows = dataRows_(appointmentsSheet_(payload));
  var booked = [];
  for (var i = 0; i < rows.length; i++) {
    var date = toDate_(rows[i][3]);
    var time = toTime_(rows[i][4]);
    if (!date || !time) continue;
    booked.push({
      name: String(rows[i][0] || "").trim(),
      service: String(rows[i][1] || "").trim(),
      phone: String(rows[i][2] || "").trim(),
      date: date,
      time: time,
      meet_link: String(rows[i][5] || "").trim(),
      booked_at: toDateTime_(rows[i][6]),
    });
  }
  return booked;
}

function listDisabled_(payload) {
  var sheet = disabledSheet_(payload, false);
  if (!sheet) return [];
  var rows = dataRows_(sheet);
  var disabled = [];
  for (var i = 0; i < rows.length; i++) {
    if (!isTrue_(rows[i][2])) continue;
    var date = toDate_(rows[i][0]);
    var time = toTime_(rows[i][1]);
    if (!date || !time) continue;
    disabled.push({ date: date, time: time });
  }
  return disabled;
}

/* ---------- disable / enable ---------- */

function setDisabled_(payload) {
  var lock = LockService.getScriptLock();
  lock.waitLock(20000);
  try {
    var date = toDate_(payload.date);
    var time = toTime_(payload.time);
    var hidden = isTrue_(payload.hidden);
    if (!date || !time) {
      throw new Error("Appointment date and time are required.");
    }

    var sheet = disabledSheet_(payload, true);
    var rows = dataRows_(sheet);
    var foundRow = 0;
    for (var i = 0; i < rows.length; i++) {
      if (toDate_(rows[i][0]) === date && toTime_(rows[i][1]) === time) {
        foundRow = i + 2;
        break;
      }
    }

    var updatedAt = Utilities.formatDate(
      new Date(),
      "Asia/Kolkata",
      "yyyy-MM-dd HH:mm:ss",
    );

    if (hidden) {
      if (foundRow) {
        sheet
          .getRange(foundRow, 1, 1, 4)
          .setValues([[date, time, "TRUE", updatedAt]]);
      } else {
        sheet.appendRow([date, time, "TRUE", updatedAt]);
        foundRow = sheet.getLastRow();
      }
      sheet.getRange(foundRow, 1, 1, 2).setNumberFormat("@");
    } else if (foundRow) {
      sheet.deleteRow(foundRow);
    }

    return { ok: true, date: date, time: time, hidden: hidden };
  } finally {
    lock.releaseLock();
  }
}

/* ---------- book ---------- */

function book_(payload) {
  var lock = LockService.getScriptLock();
  lock.waitLock(20000);
  try {
    var date = toDate_(payload.date);
    var time = toTime_(payload.time);
    if (!date || !time) {
      throw new Error("Appointment date and time are required.");
    }

    var blockMinutes = Math.max(
      1,
      parseInt(payload.consultant_block_minutes, 10) || 45,
    );
    var existing = listBooked_(payload);
    for (var i = 0; i < existing.length; i++) {
      if (
        existing[i].date === date &&
        blocksOverlap_(existing[i].time, time, blockMinutes)
      ) {
        throw new Error("slot_taken");
      }
    }

    var disabled = listDisabled_(payload);
    for (var d = 0; d < disabled.length; d++) {
      if (disabled[d].date === date && disabled[d].time === time) {
        throw new Error("That time slot is not offered.");
      }
    }

    var bookedAt = Utilities.formatDate(
      new Date(),
      "Asia/Kolkata",
      "yyyy-MM-dd HH:mm:ss",
    );
    var meetLink = createMeet_(payload);
    var sheet = appointmentsSheet_(payload);
    sheet.appendRow([
      String(payload.name || ""),
      String(payload.service || ""),
      String(payload.phone || ""),
      date,
      time,
      meetLink,
      bookedAt,
    ]);
    sheet.getRange(sheet.getLastRow(), 4, 1, 2).setNumberFormat("@");

    return { ok: true, meet_link: meetLink, booked_at: bookedAt };
  } finally {
    lock.releaseLock();
  }
}

function createMeet_(payload) {
  var event = Calendar.Events.insert(
    {
      summary: "Eat Rrite appointment – " + (payload.name || "Client"),
      description: [
        "Service: " + (payload.service || ""),
        "Phone: " + (payload.phone || ""),
        "Payment ID: " + (payload.payment_id || ""),
      ].join("\n"),
      start: { dateTime: payload.start_iso, timeZone: "Asia/Kolkata" },
      end: { dateTime: payload.end_iso, timeZone: "Asia/Kolkata" },
      conferenceData: {
        createRequest: {
          requestId: Utilities.getUuid(),
          conferenceSolutionKey: { type: "hangoutsMeet" },
        },
      },
    },
    "primary",
    { conferenceDataVersion: 1 },
  );

  if (event.hangoutLink) return event.hangoutLink;

  var points =
    event.conferenceData && event.conferenceData.entryPoints
      ? event.conferenceData.entryPoints
      : [];
  for (var i = 0; i < points.length; i++) {
    if (points[i].entryPointType === "video" && points[i].uri) {
      return points[i].uri;
    }
  }

  throw new Error("Google Meet link could not be created.");
}

/* ---------- cancel ---------- */

function cancel_(payload) {
  var lock = LockService.getScriptLock();
  lock.waitLock(20000);
  try {
    var date = toDate_(payload.date);
    var time = toTime_(payload.time);
    var phone = String(payload.phone || "").trim();
    var name = String(payload.name || "").trim();
    if (!date || !time) {
      throw new Error("Appointment date and time are required.");
    }

    var sheet = appointmentsSheet_(payload);
    var rows = dataRows_(sheet);
    var foundRow = 0;
    var meetLink = "";

    for (var i = 0; i < rows.length; i++) {
      if (toDate_(rows[i][3]) !== date || toTime_(rows[i][4]) !== time) {
        continue;
      }
      var rowPhone = String(rows[i][2] || "").trim();
      var rowName = String(rows[i][0] || "").trim();
      if (phone && rowPhone !== phone) continue;
      if (name && rowName !== name) continue;
      foundRow = i + 2;
      meetLink = String(rows[i][5] || "").trim();
      break;
    }

    if (!foundRow) {
      throw new Error("Booking not found.");
    }

    sheet.deleteRow(foundRow);
    tryDeleteMeet_(meetLink, date, time);
    return { ok: true, date: date, time: time, cancelled: true };
  } finally {
    lock.releaseLock();
  }
}

function tryDeleteMeet_(meetLink, date, time) {
  try {
    var events = Calendar.Events.list("primary", {
      timeMin: new Date(date + "T00:00:00+05:30").toISOString(),
      timeMax: new Date(date + "T23:59:59+05:30").toISOString(),
      singleEvents: true,
      maxResults: 50,
    });
    var items = events.items || [];
    for (var i = 0; i < items.length; i++) {
      var event = items[i];
      var link = event.hangoutLink || "";
      var start =
        event.start && event.start.dateTime ? event.start.dateTime : "";
      if (
        (meetLink && link === meetLink) ||
        (time && start.indexOf("T" + time) !== -1)
      ) {
        Calendar.Events.remove("primary", event.id);
        return;
      }
    }
  } catch (error) {
    // Sheet already updated; calendar cleanup is best-effort.
  }
}

/* ---------- helpers ---------- */

function blocksOverlap_(timeA, timeB, blockMinutes) {
  var a = toMinutes_(timeA);
  var b = toMinutes_(timeB);
  if (a === null || b === null) return timeA === timeB;
  return a < b + blockMinutes && b < a + blockMinutes;
}

function toMinutes_(value) {
  var time = toTime_(value);
  if (!time) return null;
  var parts = time.split(":");
  return parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
}

function toDate_(value) {
  if (value === "" || value == null) return "";
  if (
    Object.prototype.toString.call(value) === "[object Date]" &&
    !isNaN(value)
  ) {
    return Utilities.formatDate(value, "Asia/Kolkata", "yyyy-MM-dd");
  }
  var text = String(value).trim();
  var iso = text.match(/^(\d{4}-\d{2}-\d{2})/);
  return iso ? iso[1] : "";
}

function toTime_(value) {
  if (value === "" || value == null) return "";
  if (
    Object.prototype.toString.call(value) === "[object Date]" &&
    !isNaN(value)
  ) {
    return Utilities.formatDate(value, "Asia/Kolkata", "HH:mm");
  }
  if (typeof value === "number" && isFinite(value)) {
    var mins = Math.round((value % 1) * 24 * 60);
    return pad_(Math.floor(mins / 60) % 24) + ":" + pad_(mins % 60);
  }
  var text = String(value).trim();
  var m24 = text.match(/^([01]?\d|2[0-3]):([0-5]\d)/);
  if (m24 && !/am|pm/i.test(text)) {
    return pad_(m24[1]) + ":" + m24[2];
  }
  var m12 = text.match(/^(\d{1,2}):([0-5]\d)\s*([AaPp][Mm])/);
  if (m12) {
    var hour = parseInt(m12[1], 10);
    if (m12[3].toUpperCase() === "PM" && hour < 12) hour += 12;
    if (m12[3].toUpperCase() === "AM" && hour === 12) hour = 0;
    return pad_(hour) + ":" + m12[2];
  }
  return "";
}

function toDateTime_(value) {
  if (value === "" || value == null) return "";
  if (
    Object.prototype.toString.call(value) === "[object Date]" &&
    !isNaN(value)
  ) {
    return Utilities.formatDate(value, "Asia/Kolkata", "yyyy-MM-dd HH:mm:ss");
  }
  return String(value).trim();
}

function isTrue_(value) {
  if (value === true || value === 1) return true;
  var text = String(value || "")
    .trim()
    .toLowerCase();
  return text === "true" || text === "yes" || text === "1" || text === "hidden";
}

function pad_(value) {
  var text = String(value);
  return text.length === 1 ? "0" + text : text;
}

function json_(object) {
  return ContentService.createTextOutput(JSON.stringify(object)).setMimeType(
    ContentService.MimeType.JSON,
  );
}
