# Google Apps Script — Meet only

Source of truth for the Next.js app. Paste into https://script.google.com (standalone project).

1. New project → paste `Code.gs` and `appsscript.json`
2. Services (+) → enable **Google Calendar API**
3. Project Settings → Script property `SCRIPT_SECRET` = your `APPS_SCRIPT_SECRET`
4. Deploy → New deployment → Web app → Execute as Me → Anyone
5. Copy `/exec` URL into `.env.local` as `APPS_SCRIPT_URL`
