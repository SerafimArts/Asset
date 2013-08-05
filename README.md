Asset
=====
Asset precompile for PHP.
version 0.0.1-dev

## What is Asset?

The Asset is port (in future) of RoR Asset Pipeline gem file. Now you can use this facilities:
- Less
- Scss
- Css

## Installation

### 1. Composer

Add the following dependencies to your projects composer.json file:

    "require": {
        # ..
        "serafim/asset": "dev-master"
        # ..
    }


### 2. Using

    $config = new \Asset\Config([
        'cache' => __DIR__ . '/assets/', # save file into `assets/` directory
        'url'   => '/assets/' # url path
    ]);

    $f = new \Asset\File('test.scss', $config); # create asset file
    echo $f->compile('css'); # compile and get result url


