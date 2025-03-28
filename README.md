# CPP Payment Gateway

#### Requirement
- PHP version >= 8.0
- Mysql

#### Installation
Clone this project or download

#### Setup
- ###### Create `.env` file by copy `.env.example`
    ```bash
    cp .env.example .env
    ```
- ###### Install packages
    ```bash
    composer install
    ```
- ###### Generate key
    ```bash
    php artisan key:generate
    ```
- ###### Setup database
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=ccp_gateway
    DB_USERNAME=root
    DB_PASSWORD=
    ```
- ###### Migration and Seeder
    ```bash
    php artisan migrate:fresh --seed
    ```
