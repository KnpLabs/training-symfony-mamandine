# Create API with Guard and JWT

## Requirements

- Install `lcobucci/jwt`
- Create a script to generate RSA Keys (see: `bin/generate-rsa-keys`)
- Generate RSA keys

## Configuration

In security configuration file, create two new firewalls.
- One without security to create a new token when you are not authenticated.
- One using our new Authenticator to secure our API with JWT Token

## Use the library

Create a class to encode / decode a token.

The library will need RSA keys to encode / decode tokens, you will need to provide the RSA keys you created while running the following command : `bin/generate-rsa-keys var`.

You can use `.env` or store the paths in your Symfony parameters.

Copy / Paste `Reader`, `Encoder`, `UserTokenGenerator`, `AuthenticationToken` and `UsernameResolver` classes to be able to generate tokens using this library.

`UserTokenGenerator` is a factory to create a token for a given user.

The token is storing this informations:

- User informations ( email / username )
- Creation date
- Expiration date

Before creating a token, it will checks its credentials are ok by using Symfony Security Encoder.

## Create your small API

Create two new Controllers in a new namespace `Api` with following methods.

- CakeController
  - list()
- TokenController
  - create()

Your API will return a JsonResponse. Here is an example :

```php
$token = 'mySecuredToken';

return new JsonResponse([
    'token' => $token
], 201);

```

Result:

```js
{
    token: 'mySecuredToken'
}
```

Create the routes matching your new methods.


## Secure your API

Create your new Authenticator : `JWTAuthenticator`. It will extends `AbstractGuardAuthenticator`.

This Authenticator will check that the User informations stored in the token exists in your database.

You can follow the Symfony documentation : [Custom Authentication System with Guard](https://symfony.com/doc/5.2/security/guard_authentication.html)
