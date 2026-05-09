# Purchase Book Application

A modern Laravel 12 and Livewire 4 application designed for efficient purchase management, featuring a reactive entry system, master record management, and legacy data migration capabilities.

## Features

-   **Reactive Purchase Entry**: Dynamic table-based entry form powered by Alpine.js and Livewire 3.
-   **Real-time Calculations**: Instant total amount updates as quantity or price changes.
-   **Master Management**: Manage Items and Brands with relational integrity.
-   **Legacy Migration Service**: Robust service to transition legacy purchase data into the new relational structure.
-   **Role-Based Access**: Restricted access to administrative features like migration and entry creation.

## Setup Steps

### 1. Prerequisites
Ensure you have the following installed:
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL

### 2. Installation
Clone the repository and navigate to the project directory:

```bash
git clone <repository-url>
cd purchase_book
```

Install PHP dependencies:
```bash
composer install
```

Install and compile assets:
```bash
npm install
npm run build
```

### 3. Configuration
Copy the environment file and generate the application key:
```bash
cp .env.example .env
php artisan key:generate
```

Configure your database settings in the `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=purchase_book
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Database Migration & Seeding
Run the migrations and seed the database with default users:
```bash
php artisan migrate --seed
```

This will create two default users:
- **Admin**: `admin@admin.com` / `123456`
- **User**: `user@example.com` / `123456`

## Legacy Data Migration

Assuming Legacy Data will be provided in form of json file "legacy_purchases.json"

### 1. Prepare JSON Data
Place your legacy data in a JSON file at `public/data/legacy_purchases.json`. The structure should be as follows:

```json
[
    {
        "item_name": "Sugar",
        "brand_name": "ABC",
        "qty": 10,
        "price": 100
    },
    {
        "item_name": "Rice",
        "brand_name": "XYZ",
        "qty": 5,
        "price": 250
    }
]
```

### 2. Execute Migration
- Log in as an **Admin** (`admin@admin.com`).
- Navigate to the **Admin Panel**.
- Click the **Run Legacy Migration** button.

> [!NOTE]
> The migration script will automatically create any missing Items or Brands and will skip records that already exist to prevent duplicates.
