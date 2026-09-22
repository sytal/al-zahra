# Error codes reference

Maps HTTP status → page behavior for this project. Companion to
`response-standards.md`.

| Code | Meaning | Behavior |
|---|---|---|
| 403 | Forbidden (policy denied) | Centered icon, "You don't have access to this page", "Back to Home" button |
| 404 | Not found | Centered icon, "Page not found", "Back to Home" button |
| 419 | Page expired (CSRF/session) | "Your session took a while — please refresh and try again" + hard-reload "Refresh" button — the ONLY intentional hard reload in the whole app |
| 500 | Server error | Centered icon, generic friendly message, "Back to Home" button — never leak exception details (`APP_DEBUG=false` in production) |

All four share the minimal centered layout variant (header+footer only, no
nav menu) per `docs/PROJECT-BLUEPRINT.md` C22.
