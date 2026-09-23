# HTTP response / error standards (docs/CLAUDE.md Section 19)

- API/JSON responses (AJAX/Livewire error payloads) follow:
  `{ "success": bool, "message": string, "data": {...} }`.
- Custom error views: `resources/views/errors/404.blade.php`,
  `419.blade.php`, `403.blade.php`, `500.blade.php` — all extend the
  public layout, branded, never Laravel defaults.
- `419` (expired page/session) shows a friendly "session expired, please
  refresh" message with a refresh button — relevant because no-reload
  navigation means users may sit on a page a long time.
- Exceptions: custom exception classes per module where meaningful
  (`app/Modules/{Module}/Exceptions/`), caught centrally in
  `bootstrap/app.php` exception handler, logged via `HasActivityLog`-style
  human-readable entries for admin-relevant failures.

See `docs/PROJECT-BLUEPRINT.md` C22 for the exact error-page content/CTA
per code (404/403/419/500).
