Asset
=====
Asset Pipline port for PHP.
version 2.0.0

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
        Asset\Config::CACHE     => __DIR__ . '/assets/',    // cache path (save path)
        Asset\Config::URL       => '/assets/',              // url link (optional - default '/')
        Asset\Config::BASE_PATH => __DIR__ . '/sources/'    // sources base dir (optional, default - current dir)
    ]);
    echo $asset->compile(['test.scss', 'test.less', 'test.css', 'test.js', 'test.coffee']);
    // or
    // echo $asset->compile('*'); - require all (see masks)
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

### 4. Require by masks
```
    /* *** */
    echo $asset->compile('*');              // require all
    echo $asset->compile('dir/*/*.ext');    // recursive require all files in "dir" directory with extension ".ext"
    echo $asset->compile('*.css');          // require all files with extension ".css"
    echo $asset->compile('path/to/f.some'); // require f.some
```

### 5. Manifest
```
    /* *** */
    echo $asset->manifest('path/to/manifest.js'); // require manifest.js

    /* sources of manifest.js */
    //= require path/to/file.js
    //= require path/*/*.coffee
```

### 6. Laravel Providers
Add to providers:
```php
    Asset\Laravel\AssetServiceProvider
```
Make directory:
```
    /app/assets/
```

For scss files, please use directory /app/assets/scss/

Using inside blade:
```
<html>
<head>
    <title>Laravel App</title>
    {{app('asset')->manifest('path/to/manifest')}}
    {{app('asset')->compile('path/to/file')}}
</head>
</html>
```


For details, see the ./examples/
