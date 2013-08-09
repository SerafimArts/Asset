Asset
=====
Asset precompile for PHP.
version 0.0.1-dev

## What is Asset?

The Asset is port (in future) of RoR Asset Pipeline gem file. Now you can use this facilities:
- Less
- Scss
- Css
- CoffeeScript
- Js

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
    // Css minfy example (this is example, CssMinify class does not exist)
    Asset\Adaptor\Css::after(function($source){
        return (new CssMinify)->minfy($sources);
    });
    
    // Compass example (this is example, ScssCompass class does not exist)
    Asset\Adaptor\Scss::before(function($source){
        return (new \ScssCompass($source))->compile();
    });
```
