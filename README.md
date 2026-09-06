# FoodCare

FoodCare is a web-based food donation management platform built with Laravel 12. The system connects donors, administrators, and volunteers to manage food donations from donation collection, inventory management, sorting, packaging, and distribution to beneficiaries.

The platform implements the FEFO (First Expired First Out) method to prioritize food items with the nearest expiration date and includes warehouse simulation and route optimization features to improve distribution efficiency.

## Features

<a href="https://trendshift.io/repositories/23518?utm_source=repository-badge&amp;utm_medium=badge&amp;utm_campaign=badge-repository-23518" target="_blank" rel="noopener noreferrer"><img src="https://trendshift.io/api/badge/repositories/23518" alt="THU-MAIC%2FOpenMAIC | Trendshift" width="250" height="55"/></a>

### Public Website

* User registration and login
* Browse donation programs
* Online food donation
* Donation history
* User profile management
* About and FAQ pages

### Admin Dashboard

* Dashboard statistics
* Donation program management
* Donation management
* Food purchase management
* Food inventory management
* FEFO inventory allocation
* Beneficiary management
* Distribution management
* Volunteer management
* Warehouse simulation
* Distribution route optimization
* Reports and analytics

### Volunteer Dashboard

* Sorting donated food
* Packaging process
* Distribution task management

## Technology Stack

* Laravel 12
* PHP 8.3+
* MySQL
* Filament 3
* Tailwind CSS
* Vite
* Chart.js
* Leaflet.js

## Installation

```bash
git clone https://github.com/USERNAME/foodcare.git
cd foodcare

composer install
npm install

cp .env.example .env
php artisan key:generate

php artisan migrate --seed

npm run build
php artisan storage:link
php artisan serve
```

## Default Accounts

| Role      | Email                                                   | Password |
| --------- | ------------------------------------------------------- | -------- |
| Admin     | [admin@foodcare.org](mailto:admin@foodcare.org)         | password |
| Volunteer | [volunteer@foodcare.org](mailto:volunteer@foodcare.org) | password |
| Donor     | [donatur@foodcare.org](mailto:donatur@foodcare.org)     | password |

## Running Tests

```bash
php artisan test
```

## Project Structure

```text
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
tests/
```

## Main Modules

* Authentication
* Food Donation
* Donation Programs
* Food Inventory
* FEFO Allocation
* Sorting
* Packaging
* Distribution
* Beneficiaries
* Warehouse Simulation
* Route Optimization
* Reports
* Dashboard Analytics

## License

This project was developed for educational and academic purposes.
