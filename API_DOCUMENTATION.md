# API Documentation

FoodCare currently exposes web routes, not a JSON API.

## Public Routes

- `GET /`: home.
- `GET /programs`: program listing.
- `GET /programs/{programDonasi}`: program detail.
- `GET /about`: about.
- `GET /faq`: FAQ.
- `GET /contact`: contact.
- `GET|POST /login`: public login.
- `GET|POST /register`: public donor registration.
- `POST /logout`: logout.

## Donatur Routes

Protected by `auth` and `role:donatur`.

- `GET /donasi-saya`: donation form and history.
- `POST /donasi-saya`: create simulated successful donation.
- `GET /profil`: profile.
- `POST /profil`: update profile.

## Filament Routes

- `/admin`: Filament panel for `admin` and `relawan`.

