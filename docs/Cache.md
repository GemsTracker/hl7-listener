# Cache

[Symfony Cache](https://symfony.com/doc/current/components/cache.html) is used as Cache component. It is an extension to the [PSR-6 caching interface](https://www.php-fig.org/psr/psr-6/).

Currently Redis, File and Null adapters have been implemented in the Factory

## Config

Configuration can be done in the `cache` namespace of the config
```php
return [
    'cache' => [
        'adapter' => 'redis'
    ],   
];
```
General options

| option | description |
| ------ | ----------- |
| adapter | cache adapter to use. Default: `null`. Available adapters: `redis`, `file`, `null`. Recommended production adapter is `redis` |
| namespace | cache namespace. Default: `''` If using multiple projects on the same redis server, make this specific for your project.       |
| default_lifetime | The default lifetime (in seconds) for cache items that do not define their own lifetime. a lifetime of `0` will not invalidate cache items automatically. Default: `0` |

### Redis options

| option | description |
| ------ | ----------- |
| dsn    | dsn address to redis. Default: `redis://localhost` |

### File options

| option | description |
| ------ | ----------- |
| directory | cache files directory. Default: `data/cache` |



## Use

You can use the cache adapter by injecting it into your class with the `\Psr\Cache\CacheItemPoolInterface`

```php 

use \Psr\Cache\CacheItemPoolInterface;

class MyClass
{
    
    protected CacheItemPoolInterface $cache

    public function __construct(CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }
}
```


### Get Cache item

```php 
$key = 'test';

$item = $this->cache->getItem($key); // Will always return a \Psr\Cache\CacheItemInterface object
$value = $item->get(); // Will default to null if cache item is not found
```

### Check if Cache item exists

```php 
$key = 'test';

$item = $this->cache->getItem($key);
$exists = $item->isHit();
```
or alternatively
```php 
$key = 'test';

$exists = $this->cache->hasItem($key);
```

### Save cache item

```php 
$key = 'test';

$item = $this->cache->hasItem($key);
$item->set($value);
$this->cache->save($item);
```

### Cache item specific expiration

Indicate with a `\DateTimeInterface`
```php 
$key = 'test';

$item = $this->cache->hasItem($key);
$item->set($value);

$expireTime = new \DateTime();
$item->expiresAt($expireTime); // Expires immediately

$this->cache->save();
```

or a `\DateInterval` or `int` time in seconds

```php 
$key = 'test';

$item = $this->cache->hasItem($key);
$item->set($value);

$expireInterval = 300; 
$expireInterval = new \DateInterval('PT5M');
$item->expiresAt($expireInterval); // Expires in 5 minutes in both cases

$this->cache->save();
```

### Delete cache item
```php 
$key = 'test';

$item = $this->cache->deleteItem($key);
```

### Clear ALL cache
```php
$booleanResult = $this->cache->clear();
```


### Tags
By default the PSR-6 adapters have been augmented with `\Symfony\Component\Cache\Adapter\TagAwareAdapter`, enabling tagging of cache items.
Cache items now also implement `\Symfony\Contracts\Cache\ItemInterface`

#### Save item with tag
```php 
$key = 'test';

$item = $this->cache->hasItem($key);
$item->set($value);

$item->tag('group');
// or 
$item->tag(['group', 'otherGroup']);

$this->cache->save();
```

#### Invalidate tags
```php 
$this->cache->invalidateTags(['group']);
```


