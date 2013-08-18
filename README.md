Asset
=====
Asset Pipline port for PHP.
version 1.1.2

https://packagist.org/packages/serafim/asset

## What is Asset?

The Asset is port (in future) of RoR Asset Pipeline gem file. Now you can use this facilities:
- Less
- Scss
- Css
- CoffeeScript
- Js
- JS and Css minifiers

## Installation

### 1. Composer

Add the following dependencies to your projects composer.json file:
```json
{
    "require": {
        "serafim/asset": "dev-master"
    }
}
```

### 2. Using
```php
    $asset = new Asset\Compiler([
        'cache' => __DIR__ . '/assets/',    // cache path
        'url'   => '/assets/'               // url link
    ]);
    echo $asset->compile(['test.scss', 'test.less', 'test.css', 'test.js', 'test.coffee']);
    // Return (example):
    // <link rel="stylesheet" href="/assets/5afedbae41974eaff65efc5163165f83.css" />
    // <script src="/assets/de91f6d25eedcebf54ecdac04a54490c.js"></script>
```
### 3. Compile callbacks
```php
    // Css minfy example (it works, really :D)
    Asset\Trigger::css(function($source){
        return Asset\Helper\CssMin::minify($source);
    });

    
    // JS minfy example (thanks to linkorb/jsmin-php package)
    Asset\Trigger::js(function($source){
        return \JSMin::minify($source);
    });
```
