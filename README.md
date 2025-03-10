# BID CALCULATOR

[View demo !](bid-calculator.m4v)

## What can be improved

* Add more love on the UI/UX
* Switch frontend for anything else (Vuejs, React, etc.)
* More unit tests (Enums, controller, End2End)
* Create specific Request object
* Use backend proxy action to call the calculator and secure the API KEY token
* Use method options() of enums to pupulate the select in the form
* Break frontend and backend apps apart completely since they are in the same apps in this MVP.

## Prerequisites

Ensure you have the following installed on your system:

- **PHP** 8.4.x ([Download](https://www.php.net/downloads))
- **Composer** 2.8.x ([Installation Guide](https://getcomposer.org/download/))
- **Node.js** 10.9.x & **npm** ([Download](https://nodejs.org/))
- **Database**: use SQLite database
- **Composer dependencies**
    - **PHP_CodeSniffer** 3.11.x
    - **PHPUnit** 11.5.x

## Project Stack

The project is built using the following technologies:

- **Laravel** 12.x
- **Laravel Sanctum** 4.x
- **SQLite**
- **Vanilla Javascript**

## Setup Instructions

Follow these steps to set up the project from scratch:

### 1. Clone the Repository

```sh
git clone https://github.com/slevesque/bid-calculator.git
cd bid-calculator
```

### 2. Configure the Environment

```sh
cp .env.example .env
```

### 3. Install Dependencies

```sh
composer install
npm install
```

### 4. Generate the Application Key

```sh
php artisan key:generate

```

### 5. Set Up the Database

Run migrations and seed the database:

```sh
php artisan migrate --seed
```

***Note:*** During seeding, an API key will be generated. Copy the key and update your .env file:

```env
BID_CALCULATOR_API_KEY=your-generated-api-key
```

This key is required to interact with the bid calculator backend.

### 6. Start the Development Servers

Open two terminal sessions:
* Start Laravel's built-in server:
```sh
php artisan serve
```
* Run Vite for frontend assets:
```sh
npm run dev
```

### 7. Access the Application

Open your browser and go to:

👉 http://localhost:8000

Enjoy the bid calculation tools! 🚀


## Running Tests

To run automated tests, use:

```sh
php artisan test
```


## Code Quality & Linting

To check for coding standard violations with the phpcs.xml rule file, run:

```sh
vendor/bin/phpcs
```

