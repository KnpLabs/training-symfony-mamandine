# Order workflow

## Description

Handling the workflow of an order by using Symfony component `symfony/workflow`.

## Workflow

Add a new worflow `order_validation` with 4 different steps

- **in_review**: Initial step
- **cancelled**
- **validated**: ROLE_ADMIN only
- **rejected**: ROLE_ADMIN only

## TODO

Create a new page listing orders.

Regular users will be able to see all their orders. Every order can be cancelled thanks to an action button.

Admin users will be able to see all orders on the database. Every order can be cancelled, validated and rejected.

## Documentation

See: [Symfony documentation](https://symfony.com/doc/current/workflow.html)
