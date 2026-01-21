# 🧪 Anton Orders Lab — Modern Laravel Ecosystem Showcase

[![Laravel 11](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![Filament V3](https://img.shields.io/badge/Filament-V3-FFB11B?style=for-the-badge&logo=filament)](https://filamentphp.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v3-06B6D4?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)

A sophisticated laboratory project demonstrating a modern Laravel-based architecture for e-commerce and B2B systems. This repository showcases advanced backend patterns, seamless integrations, and a high-performance admin interface.

---

## 🚀 Key Technical Highlights

### 🏛️ Architecture & Clean Code
- **Service Layer Pattern**: Business logic is decoupled from controllers into dedicated services (`OrderService`, `SupplierImportService`).
- **SOLID Principles**: Strict adherence to Single Responsibility and Dependency Inversion.
- **API Resources**: Clean data transformation layer for decoupled frontend/backend evolution.
- **Custom Exception Handling**: Centralized management of business-logic exceptions (`UserNotActiveException`) and system errors.

### 🛠️ The TALL Stack & Admin Panel
- **Filament V3**: A full-featured administration dashboard for managing Orders, Products, and Payments.
- **Livewire Integration**: Real-time, reactive UI components without writing complex JavaScript.
- **Dynamic Catalog**: Advanced filtering, sorting, and paginated tables powered by Livewire.

### 💳 Integrations & External Systems
- **Stripe SDK**: Secure payment processing with automated **Webhook** handling for checkout completion.
- **B2B Supplier Integration**:
    - **Catalog Import**: Robust service for syncing products from external REST APIs.
    - **Internal API Handling**: Specialized solution using `app()->handle($request)` to prevent deadlocks during local development/import.
    - **Order Export**: Automated data transmission to supplier systems.

### 🌐 SPA-Ready REST API
- **Sanctum Authentication**: Secure token-based auth for modern SPAs.
- **Advanced Query Support**: Built-in support for complex filtering (`status`, `date_range`, `user_id`) and multi-mode sorting.
- **Auto-Validation**: Strict Request Validation for all API endpoints.

---

## 🛠️ Tech Stack

- **Framework**: Laravel 11
- **Admin Panel**: Filament v3 (Livewire + Alpine.js)
- **Database**: Eloquent ORM (MySQL/PostgreSQL ready)
- **Frontend**: Tailwind CSS, Blade, Vanilla JS
- **Payments**: Stripe API
- **Testing**: PHPUnit (Feature & Unit tests)
- **Compatibility**: PHP 8.5+ ready (via custom Compatibility Provider)

---

## 🚦 Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM

### Installation

1. **Clone & Install**:
   ```bash
   git clone https://github.com/your-username/anton-orders-lab.git
   cd anton-orders-lab
   composer install
   npm install && npm run build
   ```

2. **Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database & Admin**:
   ```bash
   php artisan migrate --seed
   # Admin: admin@example.com / admin123
   ```

4. **Run Server**:
   ```bash
   php artisan serve
   ```

---

## 🧪 Testing & Quality

The project maintains high reliability through automated testing:
```bash
php artisan test
```
**Covered scenarios:** API Authentication, Order Lifecycle, Export Logic, and Global Exception Handling.

---

## 📖 API Reference (Quick View)

| Endpoint | Method | Description |
| :--- | :--- | :--- |
| `/api/login` | `POST` | Auth & Token Issue |
| `/api/orders` | `GET` | List with advanced filters |
| `/api/catalog/import` | `POST` | Trigger Supplier Sync |
| `/api/stripe/webhook` | `POST` | Stripe Event Listener |

---

## 💳 Stripe Webhook Testing
```bash
stripe listen --forward-to localhost:8000/api/stripe/webhook
stripe trigger checkout.session.completed
```

---
*Developed as a showcase for high-end Laravel development standards.*
