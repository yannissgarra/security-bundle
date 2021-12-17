# WebmunkeezSecurityBundle

This bundle unleashes a JWT based security on Symfony applications.

## Installation

Use Composer to install this bundle:

```console
$ composer require webmunkeez/security-bundle
```

Add the bundle in your application kernel:

```php
// config/bundles.php

return [
    // ...
    Webmunkeez\SecurityBundle\WebmunkeezSecurityBundle::class => ['all' => true],
    // ...
];
```