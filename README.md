# CRUD Product - Inventory Management System

A Laravel 12 application for managing product inventory and sales tracking.

## Features

- Dashboard with key metrics (total products, low stock, sales summary)
- Product management (CRUD operations)
- Sales tracking and management
- Stock level monitoring
- Professional UI with Bootstrap 5

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL
- Node.js and NPM

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/crud-product.git
cd crud-product
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install frontend dependencies:
```bash
npm install
```

4. Create a copy of the `.env` file:
```bash
cp .env.example .env
```

5. Generate an application key:
```bash
php artisan key:generate
```

6. Update the database configuration in the `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=product
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Create the database:
```bash
mysql -u root -p
> CREATE DATABASE product;
> exit
```

8. Run the migrations:
```bash
php artisan migrate
```

9. Link the storage folder to public:
```bash
php artisan storage:link
```

10. Start the development server:
```bash
php artisan serve
```

The application will be available at http://localhost:8000

## Usage

1. Access the dashboard to view summary information
2. Navigate to Products to add/edit/delete products
3. Navigate to Sales to record sales, which will automatically update product stock levels

## Project Structure

- `app/Models/Product.php` - Product model with relationship to sales
- `app/Models/Sale.php` - Sale model with relationship to products
- `app/Http/Controllers/` - Contains the controllers for Products, Sales, and Dashboard
- `database/migrations/` - Database structure definitions
- `resources/views/` - Blade templates for the user interface

## Screenshots

(Add screenshots here)

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).
