# Event Booking System API

## You can find Postman APIs File (collection) as json file named Event Booking System API.postman_collection

## Setup Instructions

1. Clone the repository:
   ```bash
   git clone https://github.com/AbdoSamirSD/Event-Booking-System.git
   cd Event-Booking-System

2. Install dependencies:
    ```bash
    composer install
    npm install

3. Configure .env:
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=event_booking
    DB_USERNAME=root
    DB_PASSWORD=

4. Generate app key:
    ```bash
    php artisan key:generate

5. Run migrations and seeders:
    ```bash
    php artisan migrate --seed

6. Start server:
    ```bash
    php artisan serve


