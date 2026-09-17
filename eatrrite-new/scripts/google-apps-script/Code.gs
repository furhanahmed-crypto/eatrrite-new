/**
 * Eat Rrite (Next.js) — Meet link only (Calendar API).
 *
 * Properties: SCRIPT_SECRET  (= APPS_SCRIPT_SECRET in .env.local)
 * Services: Google Calendar API
 * Deploy: Web app → Execute as Me → Anyone
 *
 * Action: create_meet
 */

function doPost(e) {
  try {
    var payload = JSON.parse(e.postData.contents);
    assertSecret_(payload.secret);

    if (payload.action === "create_meet") {
      return json_({ ok: true, meet_link: createMeet_(payload) });
    }

    return json_({ ok: false, error: "Unknown action." });
  } catch (error) {
    return json_({
      ok: false,
      error: error && error.message ? error.message : String(error),
      code: "error",
    });
  }
}

function doGet() {
  return json_({ ok: true, service: "eatrrite-meet" });
}

function assertSecret_(secret) {
  var expected =
    PropertiesService.getScriptProperties().getProperty("SCRIPT_SECRET") || "";
  if (!expected || secret !== expected) {
    throw new Error("Unauthorized Apps Script request.");
  }
}

function createMeet_(payload) {
  if (!payload.start_iso || !payload.end_iso) {
    throw new Error("start_iso and end_iso are required.");
  }

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
    { conferenceDataVersion: 1 }
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

function json_(object) {
  return ContentService.createTextOutput(JSON.stringify(object)).setMimeType(
    ContentService.MimeType.JSON
  );
}
