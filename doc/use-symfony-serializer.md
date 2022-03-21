# Use Symfony Serializer

## Description

The goal of this exercises is to use Symfony serializer to import / export XML files.

## Exercise 1

Create a Symfony Command to export tables from your database.

This command will generate three files : `cakes.xml`, `categories.xml` and `users.xml`.

Symfony provide a component that transform your entities into readable data ( Json / XML )

**Example**:

```php
Class Cake {
    private string $name;
    private string $description;

    // getters
}

Class Fixtures {
    public __construct()
    {
        $cake1 = new Cake('Cake 1', 'Description 1');
    }
}
```

This will result into:
```js
    {
        name: 'Cake 1',
        description: 'Description 1'
    }
```

See documentation: [The Serializer Component ](https://symfony.com/doc/current/components/serializer.html)

**Tip:** You will face circular references since there is a many to many relation between categories and cakes.
Here is how to solve it: [Handling circular references](https://symfony.com/doc/current/components/serializer.html#handling-circular-references)

You will also need to use Symfony Filesystem to create the different files.
See documentation: [The Filesystem Component](https://symfony.com/doc/current/components/filesystem.html)

## Exercise 2

Create a command to import data from an XML file / some XML files.

This command will open an XML file (the path can be hardcoded), get all entities and insert them in the database.
You will need to be sure that the command is not generating any duplicate.

**Tip:** Some entity attributes can't be correctly fetched from the database, so you can ignore some attributes in order to import the SQL columns you want.
See: [Ignoring attributes](https://symfony.com/doc/current/components/serializer.html#ignoring-attributes)


### Bonus

Exercises 1 and 2 but using JSON files.
