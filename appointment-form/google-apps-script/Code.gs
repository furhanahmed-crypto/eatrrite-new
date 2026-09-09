/**
 * Eat Rrite appointment booking – standalone Google Apps Script
 *
 * Create this from https://script.google.com (not from the sheet Extensions menu).
 * It opens the spreadsheet by ID. The Google Drive file name does not matter.
 *
 * Script properties (gear icon → Project Settings):
 *   SCRIPT_SECRET  = value of GOOGLE_APPS_SCRIPT_SECRET in .env
 *   SHEET_ID       = 1hbwqnwyzt4heR4O6lHQtCDItofUkbCH98vQxuQRaWbU
 *   TAB_NAME            = Sheet1
 *   SLOT_TIMES_TAB      = slot-times-config  (optional; PHP also sends tab_name)
 *   DISABLED_SLOTS_TAB  = disabled-slots     (optional; PHP also sends disabled_tab_name)
 *
 * Then: Services (+) → Google Calendar API
 * Deploy → New deployment → Web app → Execute as Me → Anyone
 * Paste the /exec URL into .env as GOOGLE_APPS_SCRIPT_WEBAPP_URL
 *
 * Run testSheetAccess() once in the editor to grant spreadsheet permission.
 */

const HEADERS = [
  "Name",
  "Service",
  "Phone Number",
  "Appointment Date",
  "Appointment Time",
  "Google Meet Link",
  "Appointment Booked At",
];

function doPost(e) {
  try {
    const payload = parsePayload_(e);
    assertSecret_(payload.secret);

    if (payload.action === "list") {
      return json_({ ok: true, booked: listBooked_(payload) });
    }

    if (payload.action === "list_slot_times") {
      return json_({ ok: true, config: listSlotTimes_(payload) });
    }

    if (payload.action === "list_disabled_slots") {
      return json_({ ok: true, disabled: listDisabledSlots_(payload) });
    }

    if (payload.action === "set_disabled_slot") {
      return json_(setDisabledSlot_(payload));
    }

    if (payload.action === "book") {
      return json_(book_(payload));
    }

    return json_({ ok: false, error: "Unknown action." }, 400);
  } catch (error) {
    const message = error && error.message ? error.message : String(error);
    const taken = message === "slot_taken";
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

function parsePayload_(e) {
  if (!e || !e.postData || !e.postData.contents) {
    throw new Error("Empty request.");
  }
  const payload = JSON.parse(e.postData.contents);
  if (!payload || typeof payload !== "object") {
    throw new Error("Invalid JSON.");
  }
  return payload;
}

function assertSecret_(secret) {
  const expected =
    PropertiesService.getScriptProperties().getProperty("SCRIPT_SECRET") || "";
  if (!expected || secret !== expected) {
    throw new Error("Unauthorized Apps Script request.");
  }
}

function getSheet_(payload) {
  const props = PropertiesService.getScriptProperties();
  const tabName =
    (payload && payload.tab_name) || props.getProperty("TAB_NAME") || "Sheet1";
  const sheetId =
    (payload && payload.sheet_id) || props.getProperty("SHEET_ID") || "";

  if (!sheetId) {
    throw new Error("Missing SHEET_ID. Set it in Apps Script properties.");
  }

  const spreadsheet = SpreadsheetApp.openById(sheetId);
  let sheet = spreadsheet.getSheetByName(tabName);
  if (!sheet) {
    sheet = spreadsheet.insertSheet(tabName);
  }

  ensureHeaders_(sheet);
  return sheet;
}

function testSheetAccess() {
  const sheet = getSheet_({});
  Logger.log("Connected to spreadsheet. Tab: " + sheet.getName());
}

function ensureHeaders_(sheet) {
  const range = sheet.getRange(1, 1, 1, HEADERS.length);
  const current = range.getValues()[0];
  const missing = current.every(function (value) {
    return String(value).trim() === "";
  });

  if (missing) {
    range.setValues([HEADERS]);
    sheet.getRange(1, 1, 1, HEADERS.length).setFontWeight("bold");
    sheet.setFrozenRows(1);
  }
}

function listBooked_(payload) {
  const sheet = getSheet_(payload);
  const lastRow = sheet.getLastRow();
  if (lastRow < 2) {
    return [];
  }

  const lastCol = Math.max(sheet.getLastColumn(), HEADERS.length);
  const values = sheet.getRange(2, 1, lastRow - 1, lastCol).getValues();
  const booked = [];

  values.forEach(function (row) {
    const date = normalizeDate_(row[3]);
    const time = normalizeTime_(row[4]);
    if (!date || !time) {
      return;
    }
    booked.push({
      name: String(row[0] || "").trim(),
      service: String(row[1] || "").trim(),
      phone: String(row[2] || "").trim(),
      date: date,
      time: time,
      meet_link: String(row[5] || "").trim(),
      booked_at: formatDateTime_(row[6]),
    });
  });

  return booked;
}

function listSlotTimes_(payload) {
  const spreadsheet = SpreadsheetApp.openById(
    (payload && payload.sheet_id) ||
      PropertiesService.getScriptProperties().getProperty("SHEET_ID") ||
      "",
  );
  const tabName = (payload && payload.tab_name) || "slot-times-config";
  const sheet = spreadsheet.getSheetByName(tabName);
  if (!sheet) {
    return { found: false, settings: {}, windows: [] };
  }

  const lastRow = sheet.getLastRow();
  if (lastRow < 2) {
    return { found: false, settings: {}, windows: [] };
  }

  const lastCol = Math.max(sheet.getLastColumn(), 6);
  const values = sheet.getRange(2, 1, lastRow - 1, lastCol).getValues();
  const settings = {};
  const windows = [];

  values.forEach(function (row) {
    const type = String(row[0] || "")
      .trim()
      .toLowerCase();
    if (type === "setting") {
      const key = String(row[1] || "").trim();
      if (key) {
        settings[key] = String(
          row[5] !== "" && row[5] != null ? row[5] : row[4] || "",
        ).trim();
      }
      return;
    }
    if (type === "window") {
      windows.push({
        weekday: String(row[2] || "")
          .trim()
          .toLowerCase(),
        start: formatClock_(row[3]),
        end: formatClock_(row[4]),
      });
    }
  });

  return { found: windows.length > 0, settings: settings, windows: windows };
}

const DISABLED_HEADERS = ["Date", "Time", "Disabled", "Updated At"];

function disabledTabName_(payload) {
  return (
    (payload && payload.disabled_tab_name) ||
    PropertiesService.getScriptProperties().getProperty("DISABLED_SLOTS_TAB") ||
    "disabled-slots"
  );
}

function getDisabledSheet_(payload, createIfMissing) {
  const spreadsheet = SpreadsheetApp.openById(
    (payload && payload.sheet_id) ||
      PropertiesService.getScriptProperties().getProperty("SHEET_ID") ||
      "",
  );
  const tabName = disabledTabName_(payload);
  let sheet = spreadsheet.getSheetByName(tabName);
  if (!sheet) {
    if (!createIfMissing) {
      return null;
    }
    sheet = spreadsheet.insertSheet(tabName);
    sheet
      .getRange(1, 1, 1, DISABLED_HEADERS.length)
      .setValues([DISABLED_HEADERS]);
    sheet.getRange(1, 1, 1, DISABLED_HEADERS.length).setFontWeight("bold");
    sheet.setFrozenRows(1);
    sheet.getRange("A:B").setNumberFormat("@");
  }
  return sheet;
}

function isTruthyDisabled_(value) {
  if (value === true || value === 1) {
    return true;
  }
  const text = String(value || "")
    .trim()
    .toLowerCase();
  return text === "true" || text === "yes" || text === "1" || text === "hidden";
}

function listDisabledSlots_(payload) {
  const sheet = getDisabledSheet_(payload, false);
  if (!sheet || sheet.getLastRow() < 2) {
    return [];
  }

  const lastCol = Math.max(sheet.getLastColumn(), DISABLED_HEADERS.length);
  const values = sheet
    .getRange(2, 1, sheet.getLastRow() - 1, lastCol)
    .getValues();
  const disabled = [];

  values.forEach(function (row) {
    if (!isTruthyDisabled_(row[2])) {
      return;
    }
    const date = normalizeDate_(row[0]);
    const time = formatClock_(row[1]);
    if (!date || !time) {
      return;
    }
    disabled.push({ date: date, time: time });
  });

  return disabled;
}

function isDisabledSlot_(payload, date, time) {
  return listDisabledSlots_(payload).some(function (row) {
    return row.date === date && row.time === time;
  });
}

function setDisabledSlot_(payload) {
  const lock = LockService.getScriptLock();
  lock.waitLock(20000);

  try {
    const date = normalizeDate_(payload.date);
    const time = normalizeTime_(payload.time);
    const hidden =
      payload.hidden === true ||
      payload.hidden === "true" ||
      payload.hidden === 1 ||
      payload.hidden === "1";

    if (!date || !time) {
      throw new Error("Appointment date and time are required.");
    }

    const sheet = getDisabledSheet_(payload, true);
    const lastRow = sheet.getLastRow();
    let foundRow = 0;

    if (lastRow >= 2) {
      const lastCol = Math.max(sheet.getLastColumn(), DISABLED_HEADERS.length);
      const values = sheet.getRange(2, 1, lastRow - 1, lastCol).getValues();
      for (let i = 0; i < values.length; i++) {
        const rowDate = normalizeDate_(values[i][0]);
        const rowTime = formatClock_(values[i][1]);
        if (rowDate === date && rowTime === time) {
          foundRow = i + 2;
          break;
        }
      }
    }

    const updatedAt = Utilities.formatDate(
      new Date(),
      "Asia/Kolkata",
      "yyyy-MM-dd HH:mm:ss",
    );

    if (hidden) {
      if (foundRow) {
        sheet
          .getRange(foundRow, 1, 1, DISABLED_HEADERS.length)
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

function book_(payload) {
  const lock = LockService.getScriptLock();
  lock.waitLock(20000);

  try {
    const date = normalizeDate_(payload.date);
    const time = normalizeTime_(payload.time);

    if (!date || !time) {
      throw new Error("Appointment date and time are required.");
    }

    const existing = listBooked_(payload);
    const blockMinutes = Math.max(
      1,
      parseInt(payload.consultant_block_minutes, 10) || 45,
    );
    const taken = existing.some(function (row) {
      return (
        row.date === date && occupanciesOverlap_(row.time, time, blockMinutes)
      );
    });

    if (taken) {
      throw new Error("slot_taken");
    }

    if (isDisabledSlot_(payload, date, time)) {
      throw new Error("That time slot is not offered.");
    }

    const bookedAt = Utilities.formatDate(
      new Date(),
      "Asia/Kolkata",
      "yyyy-MM-dd HH:mm:ss",
    );
    const meetLink = createMeetLink_(payload);
    const sheet = getSheet_(payload);

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

    return {
      ok: true,
      meet_link: meetLink,
      booked_at: bookedAt,
    };
  } finally {
    lock.releaseLock();
  }
}

function createMeetLink_(payload) {
  const startIso = payload.start_iso;
  const endIso = payload.end_iso;
  const title = "Eat Rrite appointment – " + (payload.name || "Client");

  const event = Calendar.Events.insert(
    {
      summary: title,
      description: [
        "Service: " + (payload.service || ""),
        "Phone: " + (payload.phone || ""),
        "Payment ID: " + (payload.payment_id || ""),
      ].join("\n"),
      start: { dateTime: startIso, timeZone: "Asia/Kolkata" },
      end: { dateTime: endIso, timeZone: "Asia/Kolkata" },
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

  if (event.hangoutLink) {
    return event.hangoutLink;
  }

  if (event.conferenceData && event.conferenceData.entryPoints) {
    const video = event.conferenceData.entryPoints.filter(function (entry) {
      return entry.entryPointType === "video" && entry.uri;
    })[0];
    if (video) {
      return video.uri;
    }
  }

  throw new Error(
    "Google Meet link could not be created. Enable Calendar API in Apps Script.",
  );
}

function normalizeDate_(value) {
  if (!value && value !== 0) {
    return "";
  }

  if (
    Object.prototype.toString.call(value) === "[object Date]" &&
    !isNaN(value)
  ) {
    return Utilities.formatDate(value, "Asia/Kolkata", "yyyy-MM-dd");
  }

  const text = String(value).trim();
  const iso = text.match(/^(\d{4}-\d{2}-\d{2})/);
  if (iso) {
    return iso[1];
  }

  const parsed = new Date(text);
  if (!isNaN(parsed.getTime())) {
    return Utilities.formatDate(parsed, "Asia/Kolkata", "yyyy-MM-dd");
  }

  return "";
}

function formatClock_(value) {
  if (typeof value === "number" && isFinite(value)) {
    const minutes = Math.round((value % 1) * 24 * 60);
    const hour = Math.floor(minutes / 60) % 24;
    const minute = minutes % 60;
    return pad_(hour) + ":" + pad_(minute);
  }
  return normalizeTime_(value);
}

function normalizeTime_(value) {
  if (!value && value !== 0) {
    return "";
  }

  if (
    Object.prototype.toString.call(value) === "[object Date]" &&
    !isNaN(value)
  ) {
    return Utilities.formatDate(value, "Asia/Kolkata", "HH:mm");
  }

  const text = String(value).trim();
  const match24 = text.match(/^([01]?\d|2[0-3]):([0-5]\d)/);
  if (match24 && !/am|pm/i.test(text)) {
    return pad_(match24[1]) + ":" + match24[2];
  }

  const match12 = text.match(/^(\d{1,2}):([0-5]\d)\s*([AaPp][Mm])/);
  if (match12) {
    let hour = parseInt(match12[1], 10);
    const minute = match12[2];
    const meridiem = match12[3].toUpperCase();
    if (meridiem === "PM" && hour < 12) hour += 12;
    if (meridiem === "AM" && hour === 12) hour = 0;
    return pad_(hour) + ":" + minute;
  }

  return "";
}

function occupanciesOverlap_(timeA, timeB, blockMinutes) {
  const startA = toMinutes_(timeA);
  const startB = toMinutes_(timeB);
  if (startA === null || startB === null) {
    return timeA === timeB;
  }
  return startA < startB + blockMinutes && startB < startA + blockMinutes;
}

function toMinutes_(value) {
  const time = normalizeTime_(value);
  if (!time) {
    return null;
  }
  const parts = time.split(":");
  const hour = parseInt(parts[0], 10);
  const minute = parseInt(parts[1], 10);
  if (isNaN(hour) || isNaN(minute)) {
    return null;
  }
  return hour * 60 + minute;
}

function formatDateTime_(value) {
  if (!value && value !== 0) {
    return "";
  }
  if (
    Object.prototype.toString.call(value) === "[object Date]" &&
    !isNaN(value)
  ) {
    return Utilities.formatDate(value, "Asia/Kolkata", "yyyy-MM-dd HH:mm:ss");
  }
  return String(value).trim();
}

function pad_(value) {
  const text = String(value);
  return text.length === 1 ? "0" + text : text;
}

function json_(object) {
  return ContentService.createTextOutput(JSON.stringify(object)).setMimeType(
    ContentService.MimeType.JSON,
  );
}
