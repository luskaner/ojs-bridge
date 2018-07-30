
# OJS Bridge

## Description

OJS Bridge allows any PHP application to interface with an Open Journal Systems (OJS) application.

## Tested Compatibility

### Main software

* Open Journal Systems:
  * 2.4.8-3 with PHP 5.6.37 (_5.6.37-1+ubuntu18.04.1+deb.sury.org+1_)
  * 3.1.1.2 with PHP 7.2.7 (_7.2.7-0ubuntu0.18.04.2_)
* Wordpress 4.9.7 ( :warning: **only with *runkit* [recommended] or *APD* extensions**):
  * WP MVC 1.3.16  

### Other software

* MySQL 5.7.22
* Apache 2.4.29 (_Ubuntu_)

## Usage (_Derived from [mains_controller.php](samples/wordpress-wpmvc-plugin/app/controllers/mains_controller.php)_)

```php
// Include the library
require_once(dirname(__FILE__) . '/libraries/ojs-bridge/ojs_bridge.inc.php');
// Instantiate it
$ojs_bridge = new OJSBridge(dirname(__FILE__) . '/path/to/OJS/directory');
// Start the OJS related operations
$ojs_bridge->start();
// ...
// End the OJS related operations
$ojs_bridge->end();
```

## Samples

* [Wordpress WP MVC Sample](samples/wordpress-wpmvc-plugin)
