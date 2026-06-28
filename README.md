# FoodCare

FoodCare is a Laravel 12 food donation platform. Donatur use the public website to register, donate, and view donation history. Admin and relawan use Filament at `/admin` for operations.

## Stack

- Laravel 12
- PHP 8.3+
- MySQL or SQLite for local tests
- Filament 3
- Tailwind CSS v4 and Vite
- Chart.js through Filament widgets
- Leaflet.js in the route optimizer page

## Core Roles

- `admin`: manages programs, donations, purchases, inventory, volunteers, beneficiaries, distributions, reports, simulation, and route optimization.
- `relawan`: handles sorting, packaging, and assigned distribution tasks in Filament.
- `donatur`: uses the public website only.

## Local Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Default seeded accounts:

- Admin: `admin@foodcare.org` / `password`
- Relawan: `volunteer@foodcare.org` / `password`
- Donatur: `donatur@foodcare.org` / `password`

## Verification

```bash
php artisan test
```

Current suite: 9 tests, 31 assertions.

## Key Services

- `DonationService`: records successful simulated payments and syncs program totals.
- `InventoryService`: creates stock and writes inventory history.
- `FEFOService`: allocates distributions with strict FEFO and reduces only `jumlah_tersedia`.
- `WarehouseSimulationService`: stores bottleneck/optimal simulation history.
- `RouteOptimizerService`: generates nearest-distance-first routes when warehouse status is `Optimal`.
- `ReportService`: prepares report data and CSV-style spreadsheet exports.

