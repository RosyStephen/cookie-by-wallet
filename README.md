Wallet API

This project implements a Wallet API using Laravel, providing endpoints to manage user wallets and purchase cookies.
Installation

    Clone the repository:

    bash

git clone <repository_url>

Navigate to the project directory:

bash

cd <project_directory>

Install dependencies:

bash

composer install

Copy the .env.example file to .env:

bash

cp .env.example .env

Generate application key:

bash

php artisan key:generate

Configure your database settings in the .env file.

Migrate the database:

bash

php artisan migrate

Serve the application:

bash

    php artisan serve

API Routes

    Add Money to Wallet: POST /api/add-money
    Buy Cookie: POST /api/buy-cookie

Web Routes

    Login Page: GET /account/login
    Registration Page: GET /account/register

Special Notes

    Proper semantics, HTTP verbs, and JSON payloads are used in API endpoints.
    Errors are logged to a log file.
    Code is wrapped in try-catch blocks for error handling.
    Validation, middleware, authentication, authorization, tokens, and gates are implemented where necessary.
    Proper checking of bad or invalid requests is performed.
    Users can add a minimum of $3 and a maximum of $100 to their wallet in a single operation.
    Users need to buy at least one cookie to perform the "buy a cookie" operation.

Output

The Laravel code is available in this repository. You can also find the Postman collection containing API endpoints exported as a .json file.