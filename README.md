# Laravel Task Manager

A simple task management application built with Laravel.

## Prerequisites

- PHP >= 7.3
- Composer
- Node.js & NPM
- MySQL or another database supported by Laravel

## Setup and Installation

1. Clone the repository:
git clone https://github.com/your-username/task-manager.git cd task-manager



2. Install PHP dependencies:
composer install



3. Create and setup .env file:
cp .env.example .env php artisan key:generate



4. Configure your database in the .env file:
DB_CONNECTION=mysql DB_HOST=127.0.0.1 DB_PORT=3306 DB_DATABASE=your_database_name DB_USERNAME=your_database_username DB_PASSWORD=your_database_password



5. Run database migrations:
php artisan migrate



6. Install frontend dependencies:
npm install



7. Compile assets:
npm run dev or build



## Running the Application

1. Start the Laravel development server:
php artisan serve



2. Open your browser and visit: `http://localhost:8000`

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).