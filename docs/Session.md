# Sessions

[Mezzio Session](https://docs.mezzio.dev/mezzio-session/) is used for sessions. In combination with [Mezzio Session Cache](https://docs.mezzio.dev/mezzio-session-cache/) Sessions are persisted in the current cache adapter.This opens the option for session storage in an external database like Redis, making sessions compatible with loadbalancing.

## Middleware
The sessions are created in the `Mezzio\Session\SessionMiddleware`. It needs to be included in the middleware of your current route to enable it.

## Config

The session settings can be set in the `mezzio-session-cache` namespace of the config

```php 
return [
    'mezzio-session-cache' => [
        'cookie_name' => 'mycookiename',
    ],
];
```

| option | description |
| ------ | ----------- |
| cookie_name | Client side cookie name. Default: `PHPSESSION` |
| cookie_secure | HTTPS only cookie. Default: `false` |
| cookie_http_only | Only available through HTTP protocol, not through e.g. javascript. Default: `false` |
| cookie_same_site | Set the same site flag. Default: 'Lax' |
| cache_expire | expiration time of the cookie. Default: `10800` |

For more options and detailed description see [Mezzio session cache config](https://docs.mezzio.dev/mezzio-session-cache/v1/config/)


## Use

### Retrieve the session from the Request

```php 

use Mezzio\Session\SessionMiddleware;

class MyHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        // or directly 
        $session = $request->getAttribute('session');
    }
}
```

The session is an instance of `Mezzio\Session\SessionInterface`

### Check if session variable exists

```php 
$session->has('myVariable');
```

### Get session variable value

```php 
$value = $session->get('myVariable');
```

or with default value

```php 
$value = $session->get('myVariable', 'MydefaultValue');
```

### Save session variable value
```
$session->set('myVariable', 'someValue');
```

### Remove session variable value
```
$session->unset('myVariable');
```

### Purge whole session
```php 
$session->clear();
```


## Flash messages

Sometimes session variables are only needed in the next (or a fixed subsequent number of) request. You can then use Flash messages.

Flash messages are based on [Mezzio Flash](https://docs.mezzio.dev/mezzio-flash/).

They need a separate middleware ```Mezzio\Flash\FlashMessageMiddleware``` registered to the route

### Retrieve the flash messenger

```php 

use Mezzio\Flash\FlashMessageMiddleware;

class MyHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $flashMessenger = $request->getAttribute(FlashMessageMiddleware::FLASH_ATTRIBUTE);
        // or directly 
        $flashMessenger = $request->getAttribute('flash');
    }
}
```

### Create a Flash message

```php 
$flashMessenger->flash('myTemporaryVariable', 'Hello!');
```

### Create a Flash message for the next 2 requests (one extra)

```php 
$flashMessenger->flash('myTemporaryVariable', 'Hello!', 2);
```

### Get a flash message
```php 
$flashMessenger->getFlash('myTemporaryVariable');
```

### Get all flash messages
```php 
$flashMessenger->getFlashes();
```


### Clear ALL flash messages
```php 
$flashMessenger->clearFlash();
```

### Keep the current flash messages for the next request (add another hop)
```php 
$flashMessenger->prolongFlash();
```
