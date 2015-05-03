IFW
====
Project for testing purposes, strongly inspired by the Yii2 framework!

Features
--------

Those features are already integrated:

- ActiveRecord (basic integration)
- Url Routing (parse rules, url creation)
- Behaviors
- Session Component
- Request Component
- Response Component
- Database Component
- Action Classes
- Terminal Commands (clis)
- Assets (only basics js and css files, copy images to cache asset folder)

Todo
----

- AR delete
- AR events (atache behavior to test addError() method)
- Database Migrations
- Basic Rest implementation
- User Auth implementation ($app->user)

INSTALLATION
============

Project directory structur:
```
├── modules
│   └── <module_name>
│       ├── actions
│       ├── controllers
│       └── models
├── public_html
├── vendor
└── views
    ├── layouts
    └── <module_name>
        └── <action_name>
```

add composer.json file inside the root folder of your project with the content:
````json
{
    "require" : {
        "nadar/ifw" : "*"
    }
}
```

run the composer installation
```
composer install --prefer-dist
```

create index.php file inside the public_html folder with following example content:
```php
require_once '../vendor/autoload.php';

$config = new \ifw\Config(dirname(__DIR__), 'test');
$config->module('test', '\\app\\modules\\test\\Module');
$config->component('db', ['dsn' => 'mysql:host=localhost;dbname=DATABASE', 'user' => 'USERNAME', 'password' => 'PASSWORD']);

ifw::init($config->get());

echo ifw::$app->run();
```
