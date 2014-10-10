Asset
=====
#### Asset Compiler for Laravel 4
[Composer package](https://packagist.org/packages/serafim/asset)


### Installation
1) Insert package name inside your `composer.json` file and update composer vendors.
```json
{
    "require": {
        "serafim/asset": "dev-master"
    }
}
```

2) Add provider line inside `config/app.php`
```
'Asset\Provider\AssetServiceProvider'
```


### Usage
1) Make Asset directories:
```
app/
    Asset/
        javascripts/
        stylesheets/
    
```
\*directory names may be different, but it is recommended naming

2) Include inside your blade template
```html
<!doctype html>
<html>
<head>
    <title>Example</title>
    {{app('asset')->make('javascripts/file.js')}}
    {{app('asset')->make('stylesheets/some.scss')}}
</head>
<body>
</body>
</html>
```

3) Refresh page and see result


### Manifest syntax
You can collect several files into one, using a special syntax inclusions within files.
```js
// = require filename.js
// = require path/to/file.coffee 

Some features not implemented yet:
// = require all/path/*
// = require all/path/recursive/**
// = require all/files/with/extension/*.js
```


### Serialization interface

Read manifest recursively (inside included files)
```php
->make('path/to/file' [, bool $includeRecursive = false]);
```

Return inline tag (example: `<style>body{}</style>`)
```php
->make('path/to/file')->getInline([array $htmlAttributes = array()]);
```

Return full asset path (example: `/var/www/public/asset/hash.js`)
```php
->make('path/to/file')->getSourcesPath();
```

Return sources
```php
->make('path/to/file')->getSources();
```

Return link tag (example: `<script src="path/to/compiled.file.js"></script>`)
```php
->make('path/to/file')->getLink([array $htmlAttributes = array()]);

// alias: ->__toString();
```









