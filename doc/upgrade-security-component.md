# Upgrade Security Component

## Upgrade dependencies to the new Symfony security sytem

First, we need to upgrade Symfony validator ( dependency of Symfony security bundle ) and Symfony security bundle to version 5.4.
Directly change the version numbers in `composer.json` and run `composer update --no-scripts`.

Start the app and you should be able to see new deprecation messages.

## Remove deprecations

For the next steps, I suggest you to follow Symfony documentation concerning security and also this Symfony documentation to create your own authenticator: [Custom Authenticator](https://symfony.com/doc/current/security/custom_authenticator.html)

### Upgrade from UserPasswordEncoder to UserPasswordHasher

See : [Hashing Plain Passwords & PasswordCredentials](https://symfonycasts.com/screencast/symfony-security/password-credentials)

You will need to change `UserFixtures.php` and `UserTokenGenerator.php`.

Quick tip: You will need to replace in your `security.yml`, all the `encoders` part by this snippet of code

```yml
password_hashers:
        App\Entity\User:
            algorithm: auto
        Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface: 'auto'
```

Once done, try to load fixtures and check the admin's password is correctly hashed using bcrypt.

### Update Symfony Security configuration file

See both documentations:
- [Security](https://symfony.com/doc/current/security.html)
- [Custom Authenticator](https://symfony.com/doc/current/security/custom_authenticator.html)

### Update  User entity

- It needs to implement : `PasswordAuthenticatedUserInterface`
- `getUsername` is deprecated in favor of `getUserIdentifier`, replace the occurences in the project
- Signature of method `getSalt` has changed, see deprecation notices to change it.

### Update our authenticators

New Symfony authenticators are way more simple to use than the old ones using guard.

You need now to extends : `AbstractAuthenticator` and implement the methods.

Little tip: The `authenticate` method is the combination of `getCredentials` and `getUser`.

You can follow Symfony Cast course to understand how `Symfony Passport` works:
[Course concerning Symfony Passport](https://symfonycasts.com/screencast/symfony-security/passport)
