php-r3volver
============

The quickdraw php micro-framework.

Description
-----------

Have you ever wished you could have a PHP framework that isn't bloated and slow
but at the same time stays out of your way and allows you to create apps or APIs
without much hassle? Something in between Symfony and Silex or Slim maybe?

`r3volver` tries to be just that. A simple, thin layer of PHP glue allowing the
Slim Framework to be used to develop fast, yet simply and beautifully organized
web apps.

`r3volver`'s direct dependencies are Slim and Symfony's YAML component. Plus
PHPUnit if you want to run the unit tests.

Features
--------

- Simple, small and clean code base
- Reduced number of dependencies
- Simplified Service Container
- Readable configuration format
- Simple access to core functionality in an application such as Services and
  Controllers

Usage
-----

Here's a quick rundown for the impatient.

Create your project directory and files:

```Shell
mkdir hello
mkdir hello/templates
touch hello/{composer.json,config.yml,HelloController.php,index.php}
touch hello/templates/hello.php
cd hello
```

Configure composer.json and install: 

```JSON
{
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/r3born/php-r3volver"
        }
    ],
    "require": {
        "r3born/r3volver": "dev-master"
    }
}
```

```Shell
composer install
```

Configure your application:

```YAML
# file: hello/config.yml
r3volver:
  routes:
    hello: [GET, /hello, Hello.hello]
  controllers:
    Hello: '\HelloController'
```

Create your controller:

```PHP
<?php
// file: hello/HelloController.php

use R3born\R3volver\Controller;

class HelloController extends Controller {
    public function hello() {
        $this->app()->render('hello.php', array(
            'body' => 'Hello, World!'
        ));
    }
}
```

Create your template:

```HTML
<!DOCTYPE html>
<!-- file: hello/templates/hello.php -->
<html lang="en">
    <head><meta charset="utf-8"/></head>
    <body><?php echo $body; ?></body>
</html>
```

Create your application entry point:

```PHP
<?php // file: hello/index.php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/HelloController.php';

R3born\R3volver\Configuration::load(__DIR__ . '/config.yml');
R3born\R3volver\Services::getApp()->run();
```

Start the webserver:

```Shell
php -S 127.0.0.1:8080 -t .
```

Now go to [http://127.0.0.1:8080/hello]. You have a web application.

See the examples folder for a slightly more complex "hello" example where all
of the framework components are explained.

License
-------

This software is made available under the terms of the MIT license.
