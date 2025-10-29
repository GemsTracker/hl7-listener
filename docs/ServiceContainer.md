# Service Container

[Laminas Service manager](https://docs.laminas.dev/laminas-servicemanager/) is used as Service container. It is an extension to the [PSR-11 Container interface](https://www.php-fig.org/psr/psr-11/).

## Auto Injection
Classes loaded through the service container without a specific factory, will fall back to the `Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory`. 
This factory uses Reflection to check which dependencies are needed in the constructor of the specific class and tries to find them in the service manager.

## Manual Injection

Classes where the dependencies cannot be resolved through the service manager (e.g. because they need specific settings from the config) will need their own Factory.
A factory can be based on the `Laminas\ServiceManager\Factory\FactoryInterface;` where the `__invoke` receives the container and the requested name.

```php
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class MyClassFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): MyClass
    {
        return new MyClass();
    }
}
```

The needed factory can be configured in your config under the `dependencies` `factory` namespace:

```php 
return [
    'dependencies' => [
        'factories' => [
            MyClass::class => MyClassFactory::class,
        ],
    ],
];
```


## Aliasses

Sometimes it can be handy to create aliases for services.

For instance to register a specific class to an interface, so that specific implementation gets loaded when asking for the interface:
```php 
return [
    'dependencies' => [
        'aliases' => [
            MoreGenericInterface::class => MyClass::class,
        ],
    ],
];
```

or if it is needed to make the class available under a specific name

```php 
return [
    'dependencies' => [
        'aliases' => [
            'MyClass' => MyClass::class,
        ],
    ],
];
```


## Usage of Container

### Retrieve a class

```php 
$container->get(MyClass::class);
```

### Check if a class is registered

```php 
$container->has(MyClass::class);
```



## ProjectOverloader

The [ProjectOverloader](https://github.com/MagnaFacta/zalt-loader) is a class loader that checks if a class exists in parent namespaces.

E.g.
```php
use Zalt\Loader\ProjectOverloader;

$loader = new ProjectOverloader(['MyProject1', 'MyProject2']);

$myClass = $loader->create('MyClass', $config);
```
Will first check if MyProject1\MyClass exists and will return it, if not and MyProject2\MyClass does exist, it will return that instead.

