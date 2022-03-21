# Order a cake

## Description

Add the possibility to order a cake when the user is log in.

## Entities

Add a new `Order` entity with fields:

- **id**
- **validationStatus**: String
- **number**: Integer
- **creationDate**: datetime
- **buyer**: User Entity
- **cake**: Cake Entity

Add a new field for the entity `Cake`:
- **buyable**: Boolean

## Form

In a new Controller (`OrderController`), create a new method that will be called on a product page to buy a cake.

This form, will have two fields. The number of cakes you want to order and the cake name.

Only buyable cakes should be on the list.

The other fields of the order will be dynamically created thanks to the current date of the order creation and the user logged in.
