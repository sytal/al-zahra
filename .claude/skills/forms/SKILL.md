---
name: forms
description: How to build forms and validation for this project. Use whenever adding a new input, form, or validation rule.
---

# Validation / forms standard (docs/CLAUDE.md Section 16)

- Every form has a dedicated Form Request class — never inline
  `$request->validate()` in a controller.
- Every input component (`x-input`, `x-select`, `x-checkbox`, `x-radio`,
  `x-textarea`) shares one base styling partial for border/focus/error
  states — validation errors look identical everywhere, no per-form custom
  error styling.
- Every form submit uses Livewire `wire:submit` — no full page reload,
  loading state shown via the shared button component's `wire:loading`
  slot.
- File uploads: mime-type + max-size validated in the Form Request, never
  trust the client-provided extension (docs/CLAUDE.md Section 18).
- Public forms (contact, consultation, login, register) need a named
  `throttle` rate limiter defined once in `RouteServiceProvider`
  (docs/CLAUDE.md Section 18).
