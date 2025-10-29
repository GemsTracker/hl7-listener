# Translation

use \Zalt\Base\TranslationInterface

Translations are done through the `_()` function.

Optionally you can use the `\Zalt\Base\Translatable `trait for backwards compatibility of having the `_()` function in the current class itself.

Plurals can best be done through the `plural()` function, to ensure multiple translation engines can be supported.

Currently, translations are done through gettext `.po` and `.mo` files.

## Adding module translations

Add translations in the modules configprovider:

```
'translations' => [
    'paths' => [
        '<moduleName>' => [__DIR__ . '/../languages'],
    ],
],
```

