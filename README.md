# Restaurant Management System

This is a PHP-based restaurant management system with user registration, login, payment integration (Zarinpal), and admin features.

A full‐stack restaurant ordering website enabling customers to browse the menu, place orders, and make secure online payments. Developed the backend using MVC architecture for clean and maintainable code (Full‐Stack, MVC, jQuery, MySQL).

![App Screenshots](/image/screenshot.png)

## Features

- User registration and login
- Wishlist and menu management
- Invoice creation and payment (Zarinpal gateway)
- Admin panel for user promotion and access control
- Multilingual support (Persian locale)
- Responsive frontend with custom fonts and icons

## Project Structure

- `config.php` – Main configuration (DB, routes, payment keys)
- `mvc/` – MVC structure:
  - `controller/` – Business logic (user, payment, admin)
  - `model/` – Database models
  - `view/` – HTML/PHP views
- `system/` – Core system utilities and helpers
- `lib/nusoap/` – NuSOAP library for SOAP web services
- `asset/` – Static assets:
  - `css/` – Stylesheets (LESS, CSS)
  - `js/` – JavaScript files
  - `font/` – Custom fonts
  - `image/` – Images

## Setup

1. Import the database from `mahsoftg_shahrbano.sql.zip`.
2. Configure database credentials in [`config.php`](config.php).
3. Set up your web server to point to the project root.
4. Make sure PHP extensions for MySQL and SOAP are enabled.

## Payment Integration

- Zarinpal merchant ID is set in [`config.php`](config.php).
- Payment handled via [`PaymentController`](mvc/controller/payment.php).

## Customization

- Edit styles in [`asset/css/base.less`](asset/css/base.less) and [`asset/css/main/style.css`](asset/css/main/style.css).
- Update locale strings in [`locale/fa.php`](locale/fa.php).

## License

This project is licensed under the MIT License.  
See [`LICENSE`](LICENSE) for details.

NuSOAP library is included under its own LGPL license.  
See [`lib/nusoap/nusoap.php`](lib/nusoap/nusoap.php) for more information.

---