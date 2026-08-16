# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

SISDM-MI — an HR information system (attendance, leave, payroll, announcements, shift scheduling) built on Laravel 10 (PHP ^8.1). Domain naming is Indonesian: Jabatan (position), Grup, Departemen, Kantor (office), Cuti Perizinan (leave/permission), Pengumuman (announcement), Tunjangan (allowance), Potongan (deduction), Riwayat Jabatan (position history), Penjadwalan Shift (shift scheduling). Branding is locale-dependent: the `__('HRIS')` key renders "HRIS" in English and "SISDM" in Indonesian.

## Commands

```bash
composer install                    # setup (on PHP 8.5 add --ignore-platform-req=php)
php artisan storage:link            # required — uploads use the public disk
php artisan migrate --seed          # schema + seeders (database/seeders)
php artisan serve                   # dev server (no npm build step — assets are vendored under public/assets)
php artisan test                    # run tests
php artisan test --filter=TestName # run a single test
vendor/bin/pint                     # code style (Laravel Pint)
```

## Architecture

- **Blade admin app** (the primary UI) — server-rendered views in `resources/views/pages/<module>/`, routed via `routes/web.php`, one resource controller per module in `app/Http/Controllers/`. Layout is `resources/views/layouts/app.blade.php` (navbar/sidebar/toolbar/action partials prefixed `_`). Frontend stack is AdminLTE 3 + **Bootstrap 4** (jQuery) — never use Bootstrap 5 idioms (`form-select`, `badge bg-*`, `data-bs-*`, `new bootstrap.Modal`); they silently break.
- **API layer** (`routes/api.php`, controllers in `app/Http/Controllers/Api/`) is Sanctum-authenticated employee self-service (mobile). API controllers are separate classes from their web counterparts — a change to one module often needs to be made in both. Shared attendance business logic lives in `app/Services/AttendanceService.php` (holiday check, shift resolution, lateness/overtime, GPS geofence) used by both attendance controllers.
- The former Vue SPA and Vite pipeline were removed; `package.json` is a stub.

### Auth & authorization

- Web auth is classic `laravel/ui` (`Auth::routes()`); API auth is Sanctum token-based via `Api/Auth/AuthController`.
- spatie/laravel-permission with two roles on the `web` guard: `admin` and `employee`. Web routes: `/home` plus the cuti-perizinan review routes sit in the `auth` group; everything else is in `['auth', 'role:admin']` in `routes/web.php`. A `renderable` in `app/Exceptions/Handler.php` turns spatie's 403 into a redirect to `/home`; policy denials (`AuthorizationException`) flash an error and redirect back.
- **Supervisors (atasan)** are not a spatie role: any user with `bawahan` (users whose `id_atasan` points at them) can review their team's leave requests on the web, enforced by `app/Policies/CutiPerizinanPolicy.php` (self-approval is blocked even for admins). The sidebar shows the Leave menu for admins or supervisors.
- The `is_admin` boolean column is the form-facing field (user create/edit radios, dashboard queries) — `UserController` and `RoleSeeder` sync it to the spatie role on every write. Never check `is_admin` for authorization; use `hasRole('admin')` / `role:admin` middleware. `database/seeders/RoleSeeder.php` must run after all user-creating seeders (it's last in `DatabaseSeeder`).
- Users are archived, not deleted: `is_archived` column with `user.archive` / `user.restore` routes.

### Conventions & gotchas

- **Page scaffold**: every page (index, create/edit, show) wraps its content in the anonymous component `<x-page :title="..." :breadcrumb="...">` (`resources/views/components/page.blade.php`) — it renders the header, breadcrumb, and flash alerts (`status`/`success`/`error`), so pages must not render their own. Forms follow one contract: card > form > card-body with `form-group` + per-field `@error`/`is-invalid`/`invalid-feedback` + `old()`, Save/Cancel in `card-footer`. Toolbar is `@include('layouts._toolbar', [...])` with optional `actions` array for secondary buttons.
- **i18n**: UI supports `en` and `id` via `SetLocale` middleware (session-based, `lang.switch` route). English strings are the translation keys themselves; Indonesian translations live in `lang/id.json`. Use `__('...')` for every user-facing string (Blade, notifications, flash messages).
- **File uploads** (attendance photos, user documents) go to the `public` disk (`->store(..., 'public')`) and are served through the `storage/` symlink. Sensitive user fields (nik, npwp, document photos) are in `$hidden` on the User model.
- **Tables**: client-side DataTables initialize on any `<table>` with the `si-datatable` class (wired in `layouts/app.blade.php`, locale-aware). The employees index is the only server-side table (`#datatable`).
- **Geofence**: `kantor.koordinat_x` = longitude, `koordinat_y` = latitude, `radius` in meters (AttendanceService defensively unswaps legacy rows where |y| > 90). `users.is_remote` exempts a user. User→kantor resolves via `currentRiwayatJabatan → jabatan → grup → departemen → kantor` (`User::kantor()`); `users` has NO `id_jabatan` column despite the fillable entry.
- **Payroll flow**: calculate (`payroll.calculate`, AJAX) → review (`payroll.review` / `markAsReviewed`) → mark as paid (`payroll.markAsPaid`, requires review, emails the payslip PDF) → slip download (`payroll.slip`, dompdf view `pages/payroll/slip.blade.php`). Tunjangan and Potongan attach by `id_payroll`.
- **Audit trail**: spatie/laravel-activitylog with explicit `activity()->causedBy(...)->performedOn(...)->log('machine.key')` calls in controllers; machine keys are translated in `pages/activity_log/index.blade.php`. Log AFTER mutations (before deletes), never log credential/NIK/NPWP values.
- **Notifications** (mail, **queued on Redis** via predis — `ShouldQueue`, 3 tries with 10s/60s/5m backoff): `LeaveRequestSubmitted` (to atasan, from API store), `LeaveRequestDecided` (to requester), `PayslipPaid` (to employee, PDF rendered in the worker). Dev needs `php artisan queue:work` running and mail goes to Mailpit (`http://localhost:8025`); failed jobs land in `failed_jobs` and are managed at `/failed-jobs` (admin). Guard every `notify()` call with an email check.
- Foreign keys follow the `id_<entity>` naming pattern (e.g. `id_jabatan`, `id_atasan`, `id_payroll`).
- **Route inflector trap**: `Route::resource('departemen', ...)` needs `->parameters(['departemen' => 'departemen'])` — Laravel singularizes it to `{departeman}` and breaks implicit binding. Check `route:list` when adding resources with Indonesian names.
