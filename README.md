# Anton Orders Lab - Laravel Project

This is a technical laboratory project built with Laravel 11, focusing on clean architecture, REST API, and integration between systems.

## Key Features & Technical Solutions

### 1. Architectural Patterns (SOLID & Clean Code)
- **Service Layer**: Business logic is encapsulated in Services (`OrderService`, `SupplierImportService`, etc.) to keep controllers thin and logic reusable.
- **Eloquent ORM**: Native Laravel ORM is used for all database interactions, ensuring readable and maintainable data access.
- **API Resources**: Data transformation is handled by `OrderResource`, separating the database structure from the API response format.
- **SOLID Compliance**: The project follows Single Responsibility (SRP), Open/Closed (OCP), and Dependency Inversion (DIP) principles.

### 2. Orders Management & API
- **Web Interface**: Standard Blade templates with sorting (Latest, Oldest, Price, Reverse) and pagination.
- **JS-Driven Table**: A dynamic table using native JavaScript to fetch data from the API without page reloads.
- **Advanced Filtering**: Support for filtering by status, user ID, and date ranges.
- **Pagination**: Consistent pagination across Web and API endpoints.

### 3. Catalog Import (Internal REST Integration)
- **Scenario**: Importing product catalog from a supplier's API.
- **Endpoint**: `POST /catalog/import`
- **Internal Handling**: To avoid deadlocks in single-threaded environments (like `php artisan serve`), the import service uses `app()->handle($request)` to call local API endpoints internally.
- **Logic**: Supports full pagination of the supplier's catalog, updating or creating products based on their external IDs.

### 4. Order Export
- **Scenario**: Exporting local orders to a supplier's REST API.
- **Endpoint**: `POST /orders/{order}/export`
- **Logic**: Sends order data (ID, customer name, product, quantity, price) to the supplier's receiving endpoint.

### 5. Exception Handling & Validation
- **Global Error Handling**: Centralized management of Business Exceptions and System Errors in `bootstrap/app.php`.
- **Custom Exceptions**: Usage of classes like `UserNotActiveException` for specific business logic errors.
- **Strict Validation**: All incoming data (Login, API Orders) is validated using Laravel's validation engine.

### 6. PHP 8.5 Compatibility
- The project includes a `Php85CompatibilityServiceProvider` to handle deprecations in the upcoming PHP 8.5 version (e.g., PDO constants), ensuring future-proof stability.

### Filament Admin Panel
The project includes a powerful administration panel built with **Filament v3**. It allows managing all entities (Orders, Products, Supplier Data) through a modern UI.

**Powered by Livewire:**
- The entire admin panel is built using the **TALL stack** (Tailwind, Alpine.js, Laravel, Livewire).
- All interactive elements (tables, forms, modals, search, pagination) are implemented via **Livewire** components, providing a seamless SPA-like experience without full page reloads.
- You can find Livewire-related logic and comments in `app/Filament/Admin/Resources/` and `app/Providers/Filament/AdminPanelProvider.php`.

- **URL**: `/admin`
- **Credentials**:
    - **Email**: `admin@example.com`
    - **Password**: `admin123`

**Available Resources:**
- **Orders**: Full management of customer orders.
- **Products**: Management of the local product catalog.
- **Supplier Orders**: Interface to view orders from the supplier's perspective (emulation).
- **Supplier Products**: Management of the supplier's available products.

---

## SPA Developer Guide (API Documentation)

This project is fully prepared for Single Page Application (SPA) development. All core functionalities are available via REST API endpoints returning JSON.

### Authentication (Laravel Sanctum)
- **Login**: `POST /api/login` (Params: `email`, `password`) -> Returns `token`.
- **Profile**: `GET /api/profile` (Header: `Authorization: Bearer {token}`) -> Returns user object.

### Orders API
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/orders` | Paginated list of all orders. |
| GET | `/api/orders/search` | Advanced filtering/search for orders. |
| POST | `/api/orders/{id}/export` | Export a specific order to the supplier system. |

**Query Parameters for Orders:**
- `sort`: `latest` (default), `oldest`, `price_asc`, `price_desc`, `reverse` (ID desc).
- `page`: Page number (e.g., `?page=2`).
- `status`: Filter by status (`paid`, `pending`).
- `user_id`: Filter by specific user ID.
- `from`/`to`: Date range filters (YYYY-MM-DD).

### Catalog Management API
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/catalog` | Paginated list of imported products in our system. |
| POST | `/api/catalog/import` | Trigger a new import from the supplier's API. |
| POST | `/api/catalog/clear` | Wipe all products from the local catalog. |

### Supplier Emulation API (For testing integration)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/supplier/products` | Paginated list of supplier's catalog. |
| POST | `/api/supplier/orders` | Endpoint that receives exported orders. |

### Expected JSON Format (Example for Orders)
```json
{
  "data": [
    {
      "id": 1,
      "name": "Customer Name",
      "product": "Product Name",
      "quantity": 5,
      "price": "100.00",
      "status": "paid",
      "created_at": "2026-01-20 15:00:00"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "total": 50
  },
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": "..."
  }
}
```

---

## Installation & Setup

1. **Clone the repository**
2. **Install dependencies**: `composer install`
3. **Setup environment**: `cp .env.example .env` and configure your database.
4. **Run migrations**: `php artisan migrate`
5. **Start the server**: `php artisan serve`
6. **Run tests**: `php artisan test`

---
*Created as part of a Laravel development laboratory.*
