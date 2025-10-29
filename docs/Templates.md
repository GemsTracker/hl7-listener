# Templating
We use [Twig](https://twig.symfony.com/) as templating engine


## Config

Adding namespaces for twig templates can be done in the `templates` `paths` namespace of the config

```php 
return [
    'paths' => [
        'test'    => [__DIR__ . '/../Templates/Test'],
    ]
];
```

## Usage

Inject The twig template renderer to your class by asking for `Mezzio\Template\TemplateRendererInterface` in your constructor

```php 
use Mezzio\Template\TemplateRendererInterface;

class MyHandler
{
    protected TemplateRenderInterface $template;

    public function __construct(TemplateRenderInterface $template)
    {
        $this->template = $template;
    }
}
```

### Render template

```php
$html = $this->template->render('app::my-template'); 
```

or with variables

```php
$data = [
    'message' => 'hello!',
];
$html = $this->template->render('app::my-template', $data); 
```
