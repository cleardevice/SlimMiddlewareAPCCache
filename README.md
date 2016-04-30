SlimMiddlewareAPCCache
============
[![Latest Stable Version](https://poser.pugx.org/cleardevice/slim-middleware-apc-cache/v/stable.svg)](https://packagist.org/packages/cleardevice/slim-middleware-apc-cache)
[![License](https://poser.pugx.org/cleardevice/slim-middleware-apc-cache/license.svg)](https://github.com/cleardevice/SlimMiddlewareAPCCache/blob/master/LICENSE)

Cache Middleware for PHP [Slim 2.* micro framework](http://www.slimframework.com/) using [APCu Cache](http://www.php.net/manual/en/book.apc.php)

Main idea from https://github.com/palanik/SlimAPCCache

## How to Install

1. Update your `composer.json` to require the `cleardevice/slim-middleware-apc-cache` package.
2. Run `composer install` to add SlimAPCCache your vendor folder.
```json
{
  "require": {
    "cleardevice/slim-middleware-apc-cache": "0.1.0"
  }
}
```

##How to Use this Middleware
```php
<?php
require ('./vendor/autoload.php');

$app = new \Slim\Slim();

use cleardevice\SlimMiddlewareAPCCache\SlimMiddlewareAPCCache;

$app->add(new SlimMiddlewareAPCCache(60, 'myapp_prefix_');

$app->get('/foo_1', function () use ($app) {
    echo "Hello Bar, default ttl";
});

$app->get('/foo_2', function () use ($app) {
    echo "Hello Bar, custom cache ttl";
    $this->app->container->set(SlimMiddlewareAPCCache::TTL_KEY, 3600);
});

$app->get('/foo_3', function () use ($app) {
    echo "Hello Bar, no cache";
    $this->app->container->set(SlimMiddlewareAPCCache::TTL_KEY, SlimMiddlewareAPCCache::TTL_NO_CACHE);
});

$app->get('/foo_4', function () use ($app) {
    echo "Hello Bar, permanent cache";
    $this->app->container->set(SlimMiddlewareAPCCache::TTL_KEY, SlimMiddlewareAPCCache::TTL_PERMANENT_CACHE);
});

$app->run();
?>
```
