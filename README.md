Asset
=====
Asset Pipline port for PHP.
version 3.0.0

https://packagist.org/packages/serafim/asset

## What is Asset?

The Asset is port (in future) of RoR Asset Pipeline gem file. Now you can use this facilities:
- Less
- Scss
- Css
- Js
- CoffeeScript


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
    $compiler = \Asset\Compiler::getInstance(
        Config::getInstance([
            Config::PATH_PUBLIC => 'public/assets',     // direct link to public path
            Config::PATH_SOURCE => 'app/assets',        // path to sources
            Config::PATH_TEMP   => 'app/temp',          // temp path
            Config::PATH_URL    => '/assets/'
        ])
    );
    echo $compiler->toSource(); // convert result to source code
        // Return (example):
        // <style>***</style>
        // <script>***</script>
    echo $compiler->toLink();   // convert result to link
        // Return (example):
        // <link rel="stylesheet" href="/assets/5afedbae41974eaff65efc5163165f83.css" />
        // <script src="/assets/de91f6d25eedcebf54ecdac04a54490c.js"></script>
```



### 6. Laravel Providers
Add to providers:
```php
    Asset\Provider\AssetServiceProvider
```

Using inside blade:
```
<html>
<head>
    <title>Laravel App</title>
    {{app('asset')->make('path/to/file')}}
</head>
</html>
```

For details, see the ./examples/
