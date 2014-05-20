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
    {{app('asset')->make('path/to/manifest')}}
</head>
</html>
```

For details, see the ./examples/
