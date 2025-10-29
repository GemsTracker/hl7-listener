# Route Grouping

With route groups, one can assign values to a range of routes.

Signature:
```php
public function routeGroup(array $groupOptions, array $routes): array
```

Here, `$routes` is an array of (normal) routes. `$groupOptions` can be an array of the following:
 * `path` - Will be prepended to all route paths
 * `middleware` - Will be prepended to the list of middleware
 * `options` - Will be merged with the route options - in case of duplicate keys the route options override the route group options

Example:
```php
...$this->routeGroup([
    'path' => '/api',
    'middleware' => [AclMiddleware::class],
    'options' => [
        'privilege' => 'some-general-privilege',
    ]
], [
    // (routes)
])
```
