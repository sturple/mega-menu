# Mega Menu

## WordPress

WordPress integration requires [Timber](https://upstatement.com/timber/).

Include the `megamenu.php` file from the `src/WordPress` directory from your plugin file or theme's `functions.php`.

Use the `menu.twig` template for your menu.

You will then be able to specify mega menu templates for menu items on the "Menu" page in the WordPress admin.  If there is no specified template, if it is not found, or if it returns empty content the `menu.twig` template attempts to fall back to a template named `megamenu.twig`.  If this cannot be found or is empty then it falls back to displaying an ordinary Bootstrap nav dropdown. 
