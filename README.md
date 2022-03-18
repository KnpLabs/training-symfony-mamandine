# Mamandine

A magic application to sell cakes.
Written in Symfony.

## Install

Build and up the stack

```
docker-compose build
docker-compose up -d
```

Set up and populate the database

```
docker-compose exec php bin/console doctrine:database:create
docker-compose exec php bin/console doctrine:migrations:migrate
docker-compose exec php bin/console doctrine:fixtures:load
```

Compile CSS/JS

```
yarn run encore dev
```

## Credentials

**Compte admin:** admin@mail.com admin
