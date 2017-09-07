Authorization + access rights module
============================

This is zf3 module that implements authorization and access rights

How to install
==============

!!This won't work without doctrine
 To install this module you should have composer. Add this inside your composer.json:

    "require": {
        "simpavl/Authorization-zf3": "master"
    }
    
Next step:

- Add the module of `config/application.config.php` under the array `modules`, insert `Authorizationzf3`.
- Copy a file named `authorizationzf3.global.php` to `config/autoload/`.
- Modify config to fit your expectations.
- Import sql query from sql folder

How to configure
==============

Edit authorizationzf3.global.php file.
- 'authcontroller' key sets name of the controller that user will be redirected to, you can also add optional keys like 'action', use full controller path
- 'controllers' key sets name of the controllers, users and actions to restrict or allow to open.

```php
\Application\Controller\IndexController::class => [
          'user' => ['index'],
          'admin' => ['index'],
        ],
```

