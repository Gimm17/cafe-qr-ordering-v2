# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 QR-code based cafe ordering system. Customers scan table QR codes to browse menus, add items to cart, and place orders. Admin staff manage menu items, tables, orders, and payments via an admin panel.

## Development Commands

```bash
# Full project setup (fresh install)
composer setup

# Development - runs server, queue, logs, and Vite in parallel
composer dev

# Build assets for production
npm run build
# or
vite build

# Watch assets during development
npm run dev
# or
vite

# Run tests
composer test
# or
php artisan test

# Run a specific test file
php artisan test --filter test_name

# Code formatting (Laravel Pint)
./vendor/bin/pint

# Run scheduled tasks manually
php artisan schedule:work

# Cleanup pending orders (scheduled every 5 minutes)
php artisan orders:cleanup-pending --minutes=10
# Dry run:
php artisan orders:cleanup-pending --minutes=10 --dry-run

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## Architecture

### Route Structure

The application uses multiple route files:

- **routes/web.php** - Public routes (homepage, table QR entry `/t/{token}`, Midtrans webhook)
- **routes/cafe.php** - Customer-facing routes under `/cafe` prefix (menu, cart, checkout, orders)
- **routes/cafe_admin.php** - Admin panel routes under `/admin` prefix
- **routes/console.php** - Scheduled tasks (cleanup of pending orders every 5 minutes)

Route files are loaded in `bootstrap/app.php` with the `then()` callback pattern.

### Key Middleware

- `cafe.table` (EnsureTableContext) - Ensures customer has a valid table session
- `admin` (EnsureAdmin) - Ensures user is authenticated as admin
- CSRF is disabled for `/midtrans/notify` webhook endpoint

### Services (Singleton Pattern)

All services are registered as singletons in `app/Providers/CafeServiceProvider.php`:

- **CartService** - Session-based cart management with modifier support
- **OrderService** - Order creation from cart with database transactions
- **QrService** - QR code generation using `endroid/qr-code` package
- **IpaymuClient/Signer** - Ipaymu payment gateway integration
- **MidtransClient** - Midtrans payment gateway (Snap token, signature verification, status check)

### Table Session Flow

1. Customer scans QR code: `/t/{token}` (token stored in `table_tokens` table)
2. `TableSessionController@enter` validates token and sets `cafe_table_id` in session
3. All `/cafe` routes require `cafe.table` middleware (valid table session)
4. Receipt page uses HMAC-signed URLs, accessible without session

### Payment Gateways

**Midtrans** (primary):
- Config: `config/midtrans.php` - uses `.env` variables for sandbox/production
- Webhook: `/midtrans/notify` (POST) - handled by `MidtransWebhookController`
- Snap token generated via `MidtransClient@createSnapToken`
- Signature verification required for webhook security

**Ipaymu** (alternative):
- Config: `config/ipaymu.php`
- Similar webhook pattern

### Order Workflow

Order statuses: `DITERIMA` → `DIPROSES` → `READY` → `SELESAI`
Payment statuses: `UNPAID` → `PENDING` → `PAID` / `FAILED` / `EXPIRED` / `REFUNDED`

Pending unpaid orders older than 10 minutes are automatically deleted by the scheduled task.

### Database Structure

Key tables:
- `cafe_tables` - Physical tables with unique `table_no`
- `table_tokens` - Rotatable tokens for QR codes (security)
- `categories` - Menu categories (with `close_order_time` setting)
- `products` - Menu items (with `is_best_seller`, `is_sold_out`, `is_active`)
- `mod_groups` / `mod_options` - Product modifiers (e.g., extra milk, sugar level)
- `product_mod_groups` - Pivot table for product-modifier relationships
- `orders` / `order_items` / `order_item_mods` - Order data
- `payments` / `payment_events` - Payment tracking
- `order_feedback` - Customer reviews
- `banners` - Homepage carousel images
- `settings` - Site-wide configuration (cafe open/close, receipt customization)

### Frontend

- **Vite** with Laravel plugin (`vite.config.js`)
- **TailwindCSS v4** via `@tailwindcss/vite` plugin
- Entry points: `resources/css/app.css`, `resources/js/app.js`
- Layouts: `cafe-layout.blade.php` (customer), `admin-layout.blade.php` (admin)
- Alpine.js for interactive components (if present)

### Admin Features

- Menu management (categories, products, modifiers)
- Table management with QR code generation/download
- Order status management
- Feedback moderation
- Sales reports
- Settings (open/close cafe, receipt customization)
- Banner management for homepage carousel

## Testing

- PHPUnit with SQLite in-memory database
- Test suites: `tests/Unit`, `tests/Feature`
- Run with `composer test` or `php artisan test`
- Configuration in `phpunit.xml`

## Environment Variables

Required for payments:
```
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_MERCHANT_ID=
MIDTRANS_IS_PRODUCTION=false
```

## Common Patterns

### Cart Items with Modifiers
Cart uses a generated key combining product ID + modifier hash to handle same-product-with-different-modifiers as separate cart lines. See `CartService@generateCartKey`.

### Receipt Authentication
Receipt URLs use HMAC tokens for security (access without session). See `OrderController@receipt`.

### QR Token Rotation
Admin can rotate table tokens to invalidate old QR codes. See `AdminTableController@rotateToken`.

## File Uploads

Product images and banners are stored in `storage/app/public`. Use `php artisan storage:link` to make them accessible via `/storage`.
