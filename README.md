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

## Documentation

- **Repository**
  - [Use Doctrine Query Builder](doc/query-builder.md)
- **Security**
  - [Security Component with Guard](doc/create-api-with-guard-and-jwt.md)
  - [Upgrade Security Component to use Passport](doc/upgrade-security-component.md)
- **Cookie Banner**
  - [Implement a cookie consent banner](doc/cookie-consent.md)
- **Commands**
  - [Create a Symfony Command to anonymize user informations](doc/create-symfony-command.md)
- **XML Parser**
  - [Use Symfony Serializer](doc/use-symfony-serializer.md)
- **Forms**
  - [Order a cake](doc/order-a-cake.md)
- **Workflow**
  - [Order workflow](doc/order-workflow.md)
- **Optimization**
  - [Pagination](doc/pagination.md)
