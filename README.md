Asset
=====
[packagist.org](https://packagist.org/packages/serafim/asset)


- [TODO](https://github.com/SerafimArts/Asset/wiki/TODO)
- [Russian Readme](https://github.com/SerafimArts/Asset/wiki/%5BRU%5D-README)

### Available extensions
 - css
 - js
 - scss
 - sass
 - less
 - coffee

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
'Serafim\Asset\Laravel\AssetServiceProvider'
```
and
```
'Asset' => 'Serafim\Asset\Laravel\AssetFacade'
```

### Usage
1) Make Asset directories:
```
app/
    assets/
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
    {{asset_link('javascripts/file.js')}}
    {{asset_link('stylesheets/some.scss')}}
</head>
<body>
</body>
</html>
```

3) Refresh page and see result


### Manifest syntax
You can collect several files into one, using a special syntax inclusions within files.
```js
// Require one file
// = require filename.js

// Require another file
// = require path/to/file.coffee

// Require all files in dir
// = require all/path/*

// Require all files in dir recursive
// = require all/path/recursive/**

// Require all files with extension
// = require all/files/with/extension/*.js

// Require all files with extension recursive
// = require all/files/with/extension/**.js
```


### Serialization interface

Read manifest
```php
Asset::make('path/to/file.any'); // or asset_link('path/to/file', [array $htmlAttributes = array()])
```

Return inline tag (example: `<style>body{}</style>`)
```php
Asset::make('path/to/file')->getInline([array $htmlAttributes = array()]);
// or asset_source('path/to/file', [array $htmlAttributes = array()])
```

Return sources
```php
Asset::make('path/to/file')->getSources();
```

Return link tag (example: `<script src="path/to/compiled.file.js"></script>`)
```php
Asset::make('path/to/file')->getLink([array $htmlAttributes = array()]);

// alias: ->__toString();
```









