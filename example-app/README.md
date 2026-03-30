# ERP BLANCO Backend

Laravel-based backend for an ERP workflow covering authentication, role-based access, customers, catalog management, sales orders, invoicing, warehouse inventory, reporting, and Stripe-powered payments.

## Overview

This backend is organized as a modular Laravel 12 application under `app/Modules/*`. It uses:

- Laravel 12
- PHP 8.2+
- Laravel Sanctum for token auth
- Spatie Permission for roles and permissions
- Spatie Model States for order and invoice state management
- Laravel Cashier + Stripe for invoice payments
- Laravel Excel for downloadable reports

## Implemented Features

### Authentication and user management

- User login with Sanctum token generation
- Authenticated `me` endpoint returning roles and permissions
- User listing, creation, update, deletion, and role assignment
- Role/permission seeding for:
  - Super Admin
  - Admin
  - Sales
  - Inventory Manager
  - Accountant

### Roles and permissions

Seeded permissions cover:

- User management
- Product management
- Order creation and approval
- Invoice viewing, creation, and payment
- Stock movement and stock adjustment
- Report viewing and exporting

### Customer management

- Create customers with profile and business data
- List customers
- Delete customers
- Customer-scoped order endpoints are wired in routes for authenticated customer flows

### Product and catalog management

- Product listing
- Product create, update, and delete
- Categories with parent/child hierarchy support
- Unit management
- Warehouse creation and listing
- Product quantities by warehouse

### Sales orders

- Create sales orders with multiple order items
- List orders with filters:
  - `status`
  - `date`
  - `created_by`
  - `low_quantity`
  - `high_quantity`
- Customer-specific order listing
- Order approval flow
- Order deletion
- Stock availability validation before order creation

### Order state workflow

Order states are implemented with model states:

- `Pending`
- `Approved`
- `Cancelled`

Approving an order triggers domain events that automatically:

- create stock movement records
- decrease per-warehouse inventory
- create the related invoice

### Inventory and stock movements

- Stock movement listing with filters:
  - `type`
  - `date`
  - `quantity`
- Stock movement creation for:
  - `in`
  - `out`
  - `adjustment`
- Product total quantity updates
- Per-warehouse inventory tracking via pivot records
- Automatic warehouse stock updates for inbound and adjustment movements
- Low-stock data used by reporting dashboard cache

### Invoicing

- Invoice creation from approved orders
- Automatic invoice item generation from order items
- Invoice listing with filters:
  - `status`
  - `date`
  - `total`
- Invoice resource responses
- Payment initiation endpoint for Stripe

### Invoice state workflow

Invoice states are implemented with model states:

- `Draft`
- `Issued`
- `PartiallyPaid`
- `Paid`
- `Refunded`

### Payments and Stripe integration

- Stripe payment intent creation for invoices
- Stripe webhook endpoint
- Signature verification using `STRIPE_WEBHOOK_SECRET`
- Payment intent success event handling
- Automatic invoice payment marking on successful Stripe webhook
- Payment transaction recording in the `payments` table

### Reporting and exports

- Cached dashboard metrics endpoint
- Manual dashboard cache refresh endpoint
- Daily invoice Excel export
- Weekly invoice Excel export
- Queued job for generating daily invoice report files
- Artisan command for refreshing dashboard cache

### Dashboard metrics currently cached

- Total orders
- Total orders cost
- Pending invoices
- Lowest inventory product summary

## Main Modules

- `Auth`
- `Customer`
- `Product`
- `Category`
- `Unit`
- `Warehouse`
- `ProductWarehouse`
- `Sales`
- `StockMovement`
- `Invoice`
- `Payment`
- `Reporting`

## API Summary

Key API groups currently exposed in `routes/api.php`:

- Auth:
  - `POST /api/login`
  - `POST /api/register`
  - `GET /api/me`
- Users and roles:
  - `GET /api/users`
  - `GET /api/roles`
  - `POST /api/users`
  - `PUT /api/users/{id}`
  - `POST /api/users/{id}/role`
  - `DELETE /api/users/{id}`
- Customers:
  - `GET /api/customers`
  - `POST /api/customers`
  - `DELETE /api/customers/{customer}`
- Products:
  - `GET /api/products`
  - `POST /api/products`
  - `PUT /api/products/{product}`
  - `DELETE /api/products/{product}`
- Orders:
  - `GET /api/orders`
  - `POST /api/orders`
  - `POST /api/orders/{order}/approve`
  - `DELETE /api/orders/{order}`
- Invoices:
  - `GET /api/invoices`
  - `POST /api/orders/{order}/invoice`
  - `POST /api/invoices/{invoice}/pay`
- Inventory:
  - `GET /api/stock-movements`
  - `POST /api/stock-movements`
  - `GET /api/warehouses`
  - `POST /api/warehouses`
  - `GET /api/productwarehouses/{warehouse}`
- Catalog helpers:
  - `GET /api/categories`
  - `POST /api/categories`
  - `GET /api/units`
  - `POST /api/units`
- Reports:
  - `GET /api/reports/dashboard`
  - `POST /api/reports/dashboard/refresh`
  - `GET /api/reports/invoices/daily`
  - `GET /api/reports/invoices/weekly`
- Webhooks:
  - `POST /api/stripe/webhook`

## Project Structure

```text
app/
  Http/
  Jobs/
  Modules/
    Category/
    Customer/
    Invoice/
    Payment/
    Product/
    ProductWarehouse/
    Reporting/
    Sales/
    StockMovement/
    Unit/
    Warehouse/
database/
  migrations/
  seeders/
docs/
routes/
```

## Getting Started

### Requirements

- PHP 8.2+
- Composer
- Node.js and npm
- MySQL or another Laravel-supported database
- Stripe account/keys for payment features

### Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
```

### Optional seeded roles and permissions

```bash
php artisan db:seed --class=RolePermissionSeeder
```

### Run locally

```bash
composer run dev
```

This starts:

- Laravel development server
- queue listener
- log viewer
- Vite dev server

## Environment Notes

Important environment values include:

```env
APP_NAME="ERP BLANCO"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=
```

## Useful Commands

```bash
php artisan migrate
php artisan test
php artisan reports:refresh-dashboard-cache
php artisan queue:listen
```

## Notes

- Orders and invoices use explicit state classes instead of plain strings.
- Several business actions are event-driven, especially after order approval and Stripe payment success.
- The backend includes a `docs/sales-order-store.md` file describing the sales order API flow.

## Current Scope

The codebase already includes the core ERP backend flows for:

- staff authentication and permissions
- customer records
- product catalog and units/categories
- warehouse inventory
- stock movement history
- order lifecycle
- invoice lifecycle
- Stripe-backed payments
- reporting and Excel exports

This README is based on the implemented backend code under `backend/example-app`.
