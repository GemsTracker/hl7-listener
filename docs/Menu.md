# Menu

Menu items are defined in the `menu` config key. Each menu item consists of the following data:
 * `name` - the route name
 * `label` - the translation key
 * `type` - the menu item rendering type (currently only `route-link-item` is available)
 * `parent` - the parent menu item to append this item to (referenced by `name`)
 * `children` - an array of child menu items

Only one of `parent` and `children` should be provided.

An example menu would look like:

```php
[
    'name' => 'gems.route-a',
    'label' => 'gems.route-a',
    'type' => 'route-link-item',
    'children' => [
        [
            'name' => 'gems.route-b',
            'label' => 'gems.route-b',
            'type' => 'route-link-item',
            'children' => [],
        ],
        [
            'name' => 'gems.route-c',
            'label' => 'gems.route-c',
            'type' => 'route-link-item',
            'children' => [
                [
                    'name' => 'gems.route-d',
                    'label' => 'gems.route-d',
                    'type' => 'route-link-item',
                ],
            ],
        ],
        [
            'name' => 'gems.route-e',
            'label' => 'gems.route-e',
            'type' => 'route-link-item',
        ],
    ],
],
[
    'name' => 'gems.route-f',
    'label' => 'gems.route-f',
    'type' => 'route-link-item',
],
[
    'name' => 'gems.route-g',
    'label' => 'gems.route-g',
    'type' => 'route-link-item',
    'parent' => 'gems.route-e',
    'children' => [
        [
            'name' => 'gems.route-g-a',
            'label' => 'gems.route-g-a',
            'type' => 'route-link-item',
        ],
        [
            'name' => 'gems.route-g-b',
            'label' => 'gems.route-g-b',
            'type' => 'route-link-item',
        ],
        [
            'name' => 'gems.route-g-c',
            'label' => 'gems.route-g-c',
            'type' => 'route-link-item',
        ],
    ],
],
```
