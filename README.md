# SISDM-MI (HRIS)

An HR information system for CV Mebel International, built on Laravel 10. It covers attendance with GPS geofencing, leave requests with supervisor approval, payroll with downloadable/emailed payslip PDFs, shift scheduling, announcements, and an admin audit log. The UI is bilingual — English (**HRIS**) and Indonesian (**SISDM**), switchable from the navbar.

**Current release: v2.0.0** (`git tag`: `v2.0.0`; the previous release is tagged `v1.0.0`). See [What's new in v2.0.0](#whats-new-in-v200).

## Features

**People & organisation**
- Employees with personal data, documents (ID card, BPJS cards, profile photo), supervisor (`id_atasan`) and job history
- Org structure: offices → departments → groups → positions, each with its own CRUD screens
- Employees are archived rather than deleted, with a dedicated Archive tab and restore

**Attendance**
- Check-in/out from the mobile app (Sanctum API) or the admin UI, with photo and reverse-geocoded address
- **GPS geofencing** against the employee's office coordinates and radius, with a remote-worker exemption
- Shift-aware: resolves the employee's shift for the day, reduces the workday value for lateness, and records overtime past shift end
- Holidays from the work calendar block clock-in automatically

**Leave (Cuti & Perizinan)**
- Employees submit requests from the mobile app; the supervisor is emailed
- **Supervisors approve/reject their own team's requests** on the web; admins see everything; self-approval is blocked for everyone
- Decisions are emailed to the requester and can be undone from the Processed Requests page
- Filter by status, type and date range

**Payroll**
- Calculate from attendance (workdays, overtime, holiday pay, BPJS employer/employee shares) with a live AJAX breakdown
- Per-payroll allowances (tunjangan) and deductions (potongan)
- Review → mark as paid workflow, with paying gated behind a completed review
- **Payslip PDFs** — downloadable from the admin UI and emailed to the employee when payroll is marked paid

**Scheduling & communication**
- Shifts with per-day activation and bulk employee assignment
- Work calendar for holidays, meetings and events, with repeat rules
- Announcements distributed to selected departments, with a detail popup on the employee dashboard

**Platform**
- Role-based access (spatie/laravel-permission) plus a supervisor capability derived from the org chart
- **Audit log** of every significant admin/supervisor action
- **Queued email** on Redis with retry/backoff and a **Failed Jobs (DLQ)** admin page
- Sanctum-authenticated API for the mobile app; bilingual UI (en/id); branded emails and PDFs

## What's new in v2.0.0

Building on the v1.0.0 foundation (`git checkout v1.0.0` for the previous release):

**Authorization & security**
- spatie/laravel-permission with `admin`/`employee` roles; every web route now requires authentication, and everything except the dashboard and leave review is admin-only
- Supervisor leave approvals enforced by `CutiPerizinanPolicy`, scoped to direct reports, with self-approval blocked even for admins
- Closed two API privilege-escalation holes: public registration could set `is_admin`, and any authenticated user could promote anyone via `PUT /api/users/{id}`

**New capabilities**
- GPS geofencing and shift-aware lateness/overtime, shared by the web and API through `app/Services/AttendanceService.php` (the API previously recorded a flat workday with no shift logic)
- Payslip PDF generation, download and email-on-payment
- Audit log at `/activity-log`
- Redis-queued notifications with 3 tries and 10s/60s/5m backoff, plus the Failed Jobs page at `/failed-jobs`
- Mailpit for local email testing

**Interface**
- Every index, form and detail page rebuilt on a shared page scaffold with consistent cards, validation display, and action rows
- Auto-applying filters on Attendance, Leave, Payroll and the Activity Log
- Full en/id coverage; the brand renders **HRIS** in English and **SISDM** in Indonesian; the Mebel International logo now appears in the app, emails and payslip PDFs

**Notable fixes**
- Department edit/update was broken by Laravel's route-parameter inflector
- Bootstrap 5 markup on a Bootstrap 4 stack left the payroll allowance/deduction modal completely dead and several badges unstyled
- Leave forms rendered blank employee dropdowns; saving an office wiped its manager; seeded office coordinates were swapped (lat/lng)
- Password reset returned raw JSON to the browser — it now redirects with a confirmation popup and sends a notification email
- Leftover "AttendanceLive" demo branding across the auth pages

The unused Vue SPA and Vite pipeline were removed; frontend assets are vendored, so there is no build step.

## Requirements

- PHP ≥ 8.1 with the usual Laravel extensions plus `gd` (payslip PDF rendering via dompdf)
- Composer
- MySQL or MariaDB
- Redis (queue backend) — `brew install redis` on macOS
- [Mailpit](https://mailpit.axllent.org) for local email testing — `brew install mailpit`
- **No Node/npm needed** — all frontend assets (AdminLTE, Bootstrap 4, jQuery, DataTables, Chart.js) are vendored under `public/assets/`; there is no build step.

## Setup

```bash
git clone <repository-url> sisdm-mi
cd sisdm-mi
composer install
cp .env.example .env
php artisan key:generate
```

Set your database credentials in `.env` (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`), then:

```bash
php artisan storage:link      # required — uploads (photos, documents) use the public disk
php artisan migrate --seed    # schema + demo data (users, shifts, attendance, roles)
brew services start redis     # queue backend
brew services start mailpit   # local mail inbox at http://localhost:8025
php artisan serve             # http://localhost:8000
php artisan queue:work --tries=3   # queue worker (separate terminal) — required for emails
```

Mail/queue `.env` values for local dev:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
QUEUE_CONNECTION=redis
REDIS_CLIENT=predis
```

> On PHP 8.5 some dependencies pin `php 8.1–8.4`; if `composer install` refuses, use `composer install --ignore-platform-req=php`.

## Default accounts

Seeded by `database/seeders/UserSeeder.php` — all passwords are `password123`:

| Name | Email | Role |
|---|---|---|
| John Doe | `johndoe@example.com` | Admin |
| Jane Smith | `janesmith@example.com` | Employee (also a supervisor of seeded staff) |
| Michael Johnson | `michaelj@example.com` | Employee (also a supervisor of seeded staff) |

The seeder also generates ~32 factory employees and assigns each a supervisor (`id_atasan`) among the three users above.

## Roles & access

Authorization uses spatie/laravel-permission (`admin` / `employee` roles on the web guard):

- **Admin** — the full web app (all modules in the sidebar).
- **Supervisor** — any user with direct reports (`id_atasan` pointing at them): dashboard + Leave Requests, scoped to their own team, with approve/reject/undo powers (`app/Policies/CutiPerizinanPolicy.php`). Self-approval is blocked for everyone, admins included.
- **Employee** — dashboard only on the web; self-service (attendance check-in, leave submission) goes through the Sanctum API (`routes/api.php`).

Roles are synced from the `is_admin` flag on every user create/edit. On an existing database, re-sync with:

```bash
php artisan db:seed --class=RoleSeeder
```

## Key flows

- **Attendance geofence** — check-in/out (web and API) validates the GPS position against the employee's office (`kantor.koordinat_x/y` = lng/lat, `radius` in meters, resolved via their current position → group → department → office). Users flagged `is_remote` are exempt. Lateness reduces `hari_kerja` (−0.25 up to 2h late, −0.5 beyond); checkout past shift end records overtime hours. Logic lives in `app/Services/AttendanceService.php`.
- **Payroll** — calculate (AJAX on the create/edit forms) → review (`/payroll/{id}/review`) → mark as paid. Marking paid requires a completed review, emails the employee their payslip PDF, and the slip is downloadable at `/payroll/{id}/slip` once reviewed.
- **Leave** — employees submit via the API (their supervisor is notified by email); supervisors/admins decide on the web; the requester is emailed the decision. Processed requests can be undone from the "Processed Requests" page.
- **Audit log** — admin-visible trail at `/activity-log` (spatie/laravel-activitylog) recording leave decisions, payroll lifecycle, employee create/update/archive/restore, and more.

## Development

```bash
php artisan test                    # run tests
php artisan test --filter=TestName  # run a single test
vendor/bin/pint                     # code style (Laravel Pint)
```

- **Locale**: switch via the navbar or `GET /lang/{en|id}`. English strings are the translation keys; Indonesian lives in `lang/id.json`.
- **Mail in dev**: emails (leave submissions/decisions, payslips, password resets) are sent through Mailpit — open the inbox at `http://localhost:8025`. Notifications are **queued on Redis**, so a `php artisan queue:work` worker must be running (restart it with `php artisan queue:restart` after code changes).
- **Failed jobs (DLQ)**: jobs that exhaust their retries (3 tries, 10s/60s/5m backoff) land in the `failed_jobs` table and are visible at `/failed-jobs` (admin sidebar → Failed Jobs) with per-job Retry/Delete and Retry All.
- **Uploads** (attendance photos, employee documents, announcement images) are stored on the `public` disk and served through the `storage/` symlink — don't skip `php artisan storage:link`.
