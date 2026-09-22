---
name: i18n
description: How to add translations and handle locale/RTL for this project. Use whenever new UI text is introduced or a page needs locale-aware layout.
---

# Localization standard (docs/CLAUDE.md Section 11)

Languages: English (`en`), Urdu (`ur`), Hindi (`hi`), Farsi (`fa`), Roman
Urdu (`ur-roman` — custom, not real ISO but treated as one).

- Static UI text (buttons, labels, nav): Laravel's native `__()` /
  `lang/{locale}/*.php`.
- Database content (articles, courses, research): `spatie/laravel-translatable`.
- Locale stored in session + `users.preferred_locale` so logged-in users
  persist their choice.
- RTL (`ur`, `fa`) triggers `dir="rtl"` on `<html>`; layout uses
  `tailwindcss-rtl` logical classes (`ps-4` not `pl-4`) everywhere — never
  hardcode `ml-`/`pl-`/`text-left` directional utilities.
- URL structure: locale prefix (`/en/articles`, `/ur/articles`) via
  `SetLocale` route-group middleware.
- Every new UI string must be added to all 5 `lang/{locale}/*.php` files in
  the same commit. English + Roman Urdu can be machine-translated directly
  with confidence. Urdu/Hindi/Farsi: provide a placeholder machine
  translation, flag with `// TODO: verify native translation` if not fully
  confident — never leave a key blank.
