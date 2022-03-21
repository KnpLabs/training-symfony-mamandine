# Use Doctrine Query Builder

## Description

We will create a new page to search a specific cake using its name.

### TODO

- Update current layout to add a search field to all pages which will redirect to a page listing cakes with name matching search term.
- Create a new `search` method in CakeRepository to filter cakes.
  - Limit to 10 cakes max
  - Order by cake name
- This should be done only using query builder functions

### Documentation

See Doctrine documentation: [Query builder](https://www.doctrine-project.org/projects/doctrine-orm/en/2.8/reference/query-builder.html)
