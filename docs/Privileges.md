# Privileges

Privilege and role usage amounts to the following:

1. Add privilege to the (module) `ConfigProvider`:
```php
    protected function getSupplementaryPrivileges(): array
    {
        return [
            'gt.setup',
        ];
    }
```
2. Make sure `AclMiddleware` is added to routes that require privilege, and add the privilege to the route in `['options']['privilege']`.

```php
    [
        'name' => 'setup.reception.index',
        'path' => '/setup/reception/index',
        'middleware' => [
            SecurityHeadersMiddleware::class,
            AclMiddleware::class,
            LegacyController::class,
        ],
        'allowed_methods' => ['GET'],
        'options' => [
            'controller' => \Gems_Default_ReceptionAction::class,
            'action' => 'index',
            'privilege' => 'gt.setup',
        ]
    ],
```

3. In the project `ConfigProvider`, give privilege to the relevant roles:
```php
    public function getRoles(): array
    {
        return [
            'definition_date' => '2022-11-23 00:00:00',
            'roles' => [
                'role-1' => ['grl_name' => 'role-1', 'grl_description' => 'role-1', 'grl_parents' => [], 'grl_privileges' => ['p-privilege-1', 'p-privilege-2', 'gt.setup']],
                'role-2' => ['grl_name' => 'role-2', 'grl_description' => 'role-2', 'grl_parents' => [], 'grl_privileges' => ['p-privilege-2', 'p-privilege-3']],
                'role-3' => ['grl_name' => 'role-3', 'grl_description' => 'role-3', 'grl_parents' => [], 'grl_privileges' => ['gt.setup']],
                'nologin' => ['grl_name' => 'nologin', 'grl_description' => 'nologin', 'grl_parents' => [], 'grl_privileges' => ['gt.setup', 'p-privilege-2']],
            ]
        ];
    }
```
