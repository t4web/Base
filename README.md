# Base

Master:
[![Build Status](https://travis-ci.org/t4web/Base.svg?branch=master)](https://travis-ci.org/t4web/Base)
[![codecov.io](http://codecov.io/github/t4web/Base/coverage.svg?branch=master)](http://codecov.io/github/t4web/Base?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/t4web/Base/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/t4web/Base/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3810c97e-7603-453f-91d5-fc704494c0e0/mini.png)](https://insight.sensiolabs.com/projects/3810c97e-7603-453f-91d5-fc704494c0e0)
[![Dependency Status](https://www.versioneye.com/user/projects/554f0043507efb7e9e00001b/badge.svg?style=flat)](https://www.versioneye.com/user/projects/554f0043507efb7e9e00001b)

ZF2 Module. A set of generic (abstract) classes which are commonly used across multiple modules.

## Contents
- [Requirements](#requirements)
- [Installation](#installation)

Requirements
------------
* [Zend Framework 2](https://github.com/zendframework/zf2) (latest master)

Installation
------------

Add this project in your composer.json:

```json
"require": {
    "t4web/base": "~1.0.0"
}
```

Now tell composer to download Base by running the command:

```bash
$ php composer.phar update
```

Enabling it in your `application.config.php`file.

```php
<?php
return array(
    'modules' => array(
        // ...
        'T4webBase',
    ),
    // ...
);
```

