# Dev login credentials

Fixed accounts created by `database/seeders/UserSeeder.php` every time you run
`php artisan migrate:fresh --seed` (local/non-production only — production
generates a random password instead).

Public site login: `/en/login` (or `/ur/login`, `/hi/login`, `/fa/login`, `/ur-roman/login`)
Admin panel: `/admin` — only Director, Admin and Editor can sign in there (Student gets 403).

| Role | Email | Password |
|---|---|---|
| Director | director@alzahra.institute | password |
| Admin | admin@alzahra.institute | password |
| Editor | editor@alzahra.institute | password |
| Student | student@alzahra.institute | password |

All accounts are pre-verified (`email_verified_at` set) and active.

## Deploy note: scheduler must run on the server

`routes/console.php` registers daily scheduled commands (sitemap generation,
course batch-start reminder emails) via Laravel's scheduler. These only
fire if the server's cron runs Laravel's scheduler every minute:

```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

Nothing to configure in code — this is a one-time server/hosting setup step.
