# Vigilant Transactions Service

## Summary

An API backend to ingest transactions data and flags suspicious ones.

## Features

- Basic Authentication using JWT
- Input Validation & Error Handling
- Standardized API Response Structure
- Resource Pagination

## Technologies / Tools / Frameworks Used
- Larvel PHP Framework
- Hdeawy API Starter Kit

### Requirements

- PHP 8.2+
- Composer
- MySQL 8.0+ / SQLite 3

## Installation

```bash
git clone https://github.com/YungPr0f/vigilant-transactions-service.git
```

```bash
cd vigilant-transactions-service
```

```bash
composer install
```

```bash
cp .env.example .env
```

## Database
- Update the `.env` file with your database credentials

### For MySQL
```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=vigilant
DB_USERNAME=root
DB_PASSWORD=password
```

### For SQLite
```bash
DB_CONNECTION=sqlite
```

```bash
touch database/database.sqlite
```

### Run Migration

```bash
php artisan migrate
```

### Configurable Parameters
```bash
TXN_MAX_AMOUNT=50000
TXN_THROTTLE_MAX=10
TXN_THROTTLE_MINS=1
```

### Generate APP_KEY and JWT_SECRET

```bash
php artisan key:generate
```

```bash
php artisan jwt:secret
```

<!-- ```bash
npm install && npm run build
``` -->

### Run the application

```bash
php artisan serve
```

## Postman Links
- Collection & Environment URL: https://drive.google.com/drive/folders/1toH3Dcsj1C9TLKfTvvu9S2bZtf-ENLvZ


