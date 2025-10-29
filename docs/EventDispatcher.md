# Event Dispatcher

[Symfony Event Dispatcher](https://symfony.com/doc/current/components/event_dispatcher.html) is used as Event Dispatcher component.

## Config

The best way to register events is to use [Event Subscribers](https://symfony.com/doc/current/components/event_dispatcher.html#using-event-subscribers). You can automatically add your event subscribers to the EventDispatcher by registering them in your ConfigProvider in the `event` namespace
```php
return [
    'event' => [
        MyEventSubscriber::class,
    ],   
];
```

## Use

You can use the Event Dispatcher by injecting it into your class with the `\Symfony\Component\EventDispatcher\EventDispatcher`

```php 

use Symfony\Component\EventDispatcher\EventDispatcher;

class MyClass
{
    protected EventDispatcher $event

    public function __construct(EventDispatcher $event)
    {
        $this->event = $event;
    }
}
```

### Dispatch event

```php 
$event  = new \Symfony\Contracts\EventDispatcher\Event();
$this->event->dispatch($event, 'something.saved');
```


### Custom Event

```php 

use Symfony\Contracts\EventDispatcher\Event;

class SomethingSavedEvent extends Event
{
    public const NAME = 'something.saved';

    protected $something;

    public function __construct(Something $something)
    {
        $this->something = $something;
    }

    public function getSomething(): Something
    {
        return $this->something;
    }
}
```


### Subscribe Event

EventSubscribers implement `Symfony\Component\EventDispatcher\EventSubscriberInterface`

The array returned in getSubscribedEvents will have the event names as keys and either an array of function names, or an array with arrays of function names and priority, higher numbers going first. 

```php 
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MyEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            SomethingSavedEvent::NAME => [
                ['FirstAction', 10],
                ['SecondAction', -10],
            ],
            SomethingDeletedEvent::NAME => [
                'FirstDeleteAction',
            ],
        ];
    }

    public function FirstSaveAction(SomethingSavedEvent $event)
    {
        // Some action with Something
    }
}
```


