# JARP - airbag recycling system (WEB)

## Table of contents

* [Usage](#usage)
* [License](#license)

## Usage
- Copy `.env.example` file to `.env` and edit database information.

- Run this command to build docker container

`docker-compose up --build -d ars-web-dev mysql`

- Go to docker container command line

`docker exec -it ars-web-dev bash`

- If first time run project, please run that command

`composer install`

`npm i`

- Create key (run this when first time)

`php artisan key:generate`

- Run `npm run watch`

- Run this command to migrate database `php artisan migrate`

- If you need sample data in local DB, you can run this command

`php artisan db:seed`

### Environment variables

| Name                             | Required | Value           | Purpose                              |
| -------------------------------- | -------- | --------------- | ------------------------------------ |
| `DB_CONNECTION`                  | true     |                 | DB Type                              |
| `DB_HOST`                        | true     |                 | MySQL hostname or IP                 |
| `DB_PORT`                        | true     |                 | MySQL Port                           |
| `DB_DATABASE`                    | true     |                 | MySQL database name                  |
| `DB_USERNAME`                    | true     |                 | MySQL username                       |
| `DB_PASSWORD`                    | true     |                 | MySQL password                       |
| `SESSION_DRIVER`                  | true    | database        | Session driver
| `SESSION_LIFETIME`                | true    | 120             | Session time out
| `APP_PORT`                        | true    | 3000            | web port

## License

UNLICENSED
