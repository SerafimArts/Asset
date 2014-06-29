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

### 2. Laravel Providers
Add to providers:
```php
    Asset\Provider\AssetServiceProvider
```

Using inside blade:
```
<html>
<head>
    <title>Laravel App</title>
    {{app('asset')->make('path/to/file.scss')}} 
    <!-- scss extension as example -->
</head>
</html>
```

### 3. Manifests
You can assemble multiple files into one, for this use manifests
```js
/* this is sources of manifest.js file */
// = require vendors/jquery.js
// = require vendors/*.js
// = require app/*
// = requrie add/controllers/*.coffee
```
<html>
<head>
    <title>Laravel App</title>
    {{app('asset')->make('assets/js/manifest.js')}}
</head>
</html>

