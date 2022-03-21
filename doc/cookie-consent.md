# Implement a cookie consent banner

## Privacy policy page

Create a new page containing the privacy strategy of your website.
This page should be in an other namespace than `Cakes` and `Security`, create a new namespace for the static pages of your website.

## Change the layout

The cookie consent banner should be present on every page of your website.
Change the layout to add a new `div` with css attribute : `position: fixed` to fix the banner to the bottom of your page.
The banner should contain a validation button to accept cookies.

## Store the user consentment in its local storage

Add some JS to store a new key in user's local storage like `cookiesAccepted` when the user will accept cookie policy.
When this key is present in the user's local storage, the cookie consent banner should not be displayed.

See Mozilla documentation: [LocalStorage](https://developer.mozilla.org/fr/docs/Web/API/Window/localStorage)
