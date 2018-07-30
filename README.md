
# OJS Bridge

## Description

OJS Bridge allows any PHP applicattion to interface with an Open Journal Systems (OJS) application

## Tested Compatibility

### Main software

* Wordpress 4.9.7 ( :warning: **only with APD or runkit PHP extensions**):
  * WP MVC 1.3.16  

* Open Journal Systems:
  * 2.4.8-3 with PHP 5.6.37 (_5.6.37-1+ubuntu18.04.1+deb.sury.org+1_)
  * 3.1.1.2 with PHP 7.2.7 (_7.2.7-0ubuntu0.18.04.2_)

### Other software

* MySQL 5.7.22
* Apache 2.4.29 (_Ubuntu_)

## Usage (_Derived from [mains_controller.php](samples/wordpress-wpmvc-plugin/app/controllers/mains_controllers.php)_)

### General

```php
// Include the library
require_once(dirname(__FILE__) . '/libraries/ojs-bridge/ojs_bridge.inc.php');
```

### Modern

```php
// It will automatically call 'startOnce', then this function and finally, 'endOnce'
OJSBridge::Instance()->doOnce(dirname(__FILE__) . '/path/to/OJS/directory', function($application){
  // ...
});
```

### Classic

```php
// Start the OJS related operations
OJSBridge::Instance()->startOnce('/path/to/OJS/directory');
// ...
// End the OJS related operations
OJSBridge::Instance()->endOnce();
```

## Samples

* [Wordpress WP MVC Sample](samples/wordpress-wpmvc-plugin)