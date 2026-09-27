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
