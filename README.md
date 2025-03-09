# Translation Management API

## Overview
The Translation Management API is a Laravel 11-based RESTful service that allows users to manage translations across multiple locales. The API supports creating, updating, deleting, searching, and exporting translations while ensuring authentication via Laravel Sanctum.

---
## Features
- Store translations for multiple locales (e.g., English, French, Spanish)  
- CRUD operations for translations  
- Search translations by locale and key  
- Export translations as JSON  
- Token-based authentication with Laravel Sanctum  
- Optimized database seeding for 100K+ records  
- Unit and feature tests with PestPHP  

---
## Technology Stack
- Backend: Laravel 11 (PHP 8+)
- Database: MySQL (or any relational DB supported by Laravel)
- Authentication: Laravel Sanctum
- Caching: Redis (for optimized performance)
- Testing: PestPHP

---
## Installation Guide

### 1. Clone the Repository
```bash
git clone https://github.com/hnabeel64/translationApp.git
cd translationApp
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Configure Environment
Copy the example `.env` file and configure your database settings:
```bash
cp .env.example .env
```
Update database credentials in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=translation_management
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Run Migrations & Seed Data
```bash
php artisan migrate --seed
```

This will create necessary tables and seed 100K+ translations for testing.

---
## Running the Application
Start the Laravel development server:
```bash
php artisan serve
```
By default, the API will be accessible at:
```
http://127.0.0.1:8000/api
```

---
## API Endpoints

### Authentication Required: All endpoints require authentication using Laravel Sanctum.

### 1. Create a Translation
```http
POST /api/translations
```
Payload:
```json
{
  "locale_id": 1,
  "key": "welcome_message",
  "content": "Welcome to our platform",
  "tags": "web"
}
```

### 2. Update a Translation
```http
PUT /api/translations/{id}
```
Payload:
```json
{
  "content": "Updated content",
  "tags": "mobile"
}
```

### 3. Delete a Translation
```http
DELETE /api/translations/{id}
```

### 4. Search Translations
```http
GET /api/translations/search/{locale_code}?key=your_search_key
```

### 5. Export Translations as JSON
```http
GET /api/translations/export
```

---
## Testing

Run the unit and feature tests with PestPHP:
```bash
php artisan test tests/Feature/TestTranslation.php
```
Run a specific test:
```bash
php artisan test tests/Feature/TestTranslation.php --filter it_can_create_a_translation
```

---
## License
This project is open-source and available under the [MIT License](LICENSE).

---
## Contributing
If you'd like to contribute, please fork the repository and submit a pull request. Make sure to write tests for any new features.

---
## Contact
For issues or suggestions, feel free to open an issue on GitHub.
