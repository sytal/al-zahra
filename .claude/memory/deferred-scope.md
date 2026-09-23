# Deferred scope — revisit after launch

These were identified as gaps but explicitly deferred by the user
(2026-09-23) to avoid unnecessary complexity before the site is live. Do
NOT build these unless the user explicitly asks, even if a future session
notices the same gap again.

- **Backup strategy** — DB + media backups (e.g. `spatie/laravel-backup`).
  Add once production data actually exists and matters.
- **Health-check / uptime endpoint** — for production monitoring. Add once
  the site is deployed and there's something to monitor.
- **CI/CD pipeline** — GitHub Actions or similar for automated
  test-run/deploy. Add once the repo has a remote + deploy target.

When any of these becomes relevant, add a proper section to
`docs/CLAUDE.md` (and a corresponding `.claude/skills/` entry if it
introduces a new standard) rather than improvising inline.
