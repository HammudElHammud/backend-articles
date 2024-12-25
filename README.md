### Laravel Project

## Introduction

This Laravel project is configured to interact with the OpenNews, The Guardian and New York Times APIS. It demonstrates how to log in to the API and fetch articles using the provided endpoint.

## Requirements

- PHP >= 8.0
- Composer
- Laravel >= 8.0
- Docker (for Docker setup)

## Installation

### Without Docker

1. Clone the repository:

    ```sh
    git clone https://github.com/HammudElHammud/backend-articles
    cd your-project
    ```

2. Install dependencies:

    ```sh
    composer install
    ```

3. Copy the example environment file and set up your environment variables:

    ```sh
    cp .env.example .env
    ```

4. Update your `.env` file with the necessary configuration:

    ```env
    NEWS_API_KEY=your_news_api_key
    GUARDIAN_API_KEY=your_guardian_api_key
    NEW_YORK_TIME_API_KEY=your_nyt_api_key

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

   **Note:** Replace `your_news_api_key`, `your_guardian_api_key`, `your_nyt_api_key`, `your_database`, `your_username`, and `your_password` with your own values.

5. Composer install:

    ```sh
    composer install
    ```
6. Set up the project, this will run for key:generate, database , seeder and Password Grant Client:

    ```sh
     php artisan setup:install
    ```

7. Run the application:

    ```sh
    php artisan serve
    ```

### With Docker

1. Clone the repository:

    ```sh
    git clone https://github.com/HammudElHammud/backend-articles
    cd your-project
    ```

2. Copy the example environment file and set up your environment variables:

    ```sh
    cp .env.example .env
    ```

3. Update your `.env` file with the necessary configuration:

    ```env
    NEWS_API_KEY=your_news_api_key
    GUARDIAN_API_KEY=your_guardian_api_key
    NEW_YORK_TIME_API_KEY=your_nyt_api_key

    WWWGROUP=1000
    WWWUSER=1000

    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=articles
    DB_USERNAME=root
    DB_PASSWORD=
    ```

   **Note:** Replace `your_news_api_key`, `your_guardian_api_key`, and `your_nyt_api_key` with your own values.

4. Build and run the Docker containers:

    ```sh
    docker-compose up --build
    ```

5. Access the application at `http://localhost:8000`.


6. Run the command to setup the project, go into the articles-backend_laravel.test_1 by run
 ```
docker exec -it articles-backend_laravel.test_1  bash

```

7. Run setup required.

```angular2html
php artisan setup:install
```

## Run both frontend and the backend on one setup
1. Make folder for the project
2. Clone the both repository inside the folder:

 ```sh
 git clone https://github.com/HammudElHammud/frontend-articles.git
 git clone https://github.com/HammudElHammud/backend-articles.git
 ```
1. Go to backend folder
2. inside docker-compose.yml file commit this first section and uncommint the commint one.
 so this should be run
```angular2html
services:
    # Backend (Laravel)
    laravel.test:
        build:
            context: ./vendor/laravel/sail/runtimes/8.4
            dockerfile: Dockerfile
            args:
                WWWGROUP: '${WWWGROUP}'
        image: sail-8.4/app
        extra_hosts:
            - 'host.docker.internal:host-gateway'
        ports:
            - '${APP_PORT:-80}:80'
            - '${VITE_PORT:-5173}:${VITE_PORT:-5173}'
        environment:
            WWWUSER: '${WWWUSER}'
            LARAVEL_SAIL: 1
            XDEBUG_MODE: '${SAIL_XDEBUG_MODE:-off}'
            XDEBUG_CONFIG: '${SAIL_XDEBUG_CONFIG:-client_host=host.docker.internal}'
            IGNITION_LOCAL_SITES_PATH: '${PWD}'
        volumes:
            - '.:/var/www/html'
        networks:
            - sail
        depends_on:
            - mysql

    # MySQL Service
    mysql:
        image: 'mysql/mysql-server:8.0'
        ports:
            - '${FORWARD_DB_PORT:-3306}:3306'
        environment:
            MYSQL_ROOT_PASSWORD: '${DB_PASSWORD}'
            MYSQL_ROOT_HOST: '%'
            MYSQL_DATABASE: '${DB_DATABASE}'
            MYSQL_USER: '${DB_USERNAME}'
            MYSQL_PASSWORD: '${DB_PASSWORD}'
            MYSQL_ALLOW_EMPTY_PASSWORD: 1
        volumes:
            - 'sail-mysql:/var/lib/mysql'
            - './vendor/laravel/sail/database/mysql/create-testing-database.sh:/docker-entrypoint-initdb.d/10-create-testing-database.sh'
        networks:
            - sail
        healthcheck:
            test:
                - CMD
                - mysqladmin
                - ping
                - '-p${DB_PASSWORD}'
            retries: 3
            timeout: 5s

    # Frontend (React)
    frontend:
        build:
            context: ../frontend-articles
        container_name: articles-frontend
        ports:
            - "3000:3000"  # Exposing port 3000 for React development server
        volumes:
            - ../frontend-articles:/app
        command: ["npm", "start"]  # This runs the React development server
        networks:
            - sail  # Connecting frontend to the same network as backend
        depends_on:
            - laravel.test  # Ensure backend is available before starting the frontend

networks:
    sail:
        driver: bridge

volumes:
    sail-mysql:
        driver: local
```

 Run the setup command on the backend container
  - go inside the container before need to build the docker compose
   ```
    docker-compose up --build -d
    docker exec -it articles-backend_laravel.test_1  bash
   ```
  - run this 
  ```
    php artisan setup:install
   ```

Note just make sure the frontend folder correct here

```angular2html
   context: ../frontend-articles
```

Node: make sure all set up for the docker on the .env file

will show the backend run on http://localhost:8000/  and the frontend on http://localhost:3000/


## Configuration

The project requires the following environment variables to be set:

- `API_TASK`: The endpoint for the BauBuddy API.
- `NEWS_API_KEY`: Your News API key.
- `GUARDIAN_API_KEY`: Your Guardian API key.
- `NEW_YORK_TIME_API_KEY`: Your New York Times API key.
- Database credentials (`DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

These variables are already included in the example environment file (`.env.example`). Make sure to replace placeholder values with your actual keys and credentials.

## Running the Application

## Without Docker

```sh
php artisan serve
```
