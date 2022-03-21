# Create a Symfony command to anonymize User informations

## Description

We will need to create a Symfony command in order to anonymize sensitive user's data.

## User entity

Add three new fields :

- Firstname
- Lastname
- Country

## Fixtures

Create 10 new "real" users in your User fixtures with role : `ROLE_USER`.

## Create a Symfony Command

Follow Symfony documentation : [Console Commands](https://symfony.com/doc/current/console.html)

This command will get all users in your database and will replace personal informations ( `firstname`, `lastname`, `country` and `email` ) with fake ones using [fakerphp](https://fakerphp.github.io/).

**Note:** We will not change our admin user.

### Requirements

- Add a description
- Add a confirmation message
- Add a progressbar
- Add a bit of styling like `Section`

**Tip:** To see the progressbar, add a timeout of 0,5s after each user anonymization. Here is the snippet:

```php
usleep(500000);
```
