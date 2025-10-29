# Deletion versus (De)activation

GemsTracker uses two systems in combination to organize "deletion". The data in certain tables is never deleted, but 
only deactivated and activated (at least not from the application interface). Data in other tables can be deleted, but 
often only when there are no foreign key constraints in the way, i.e. when the data is not used. Lastly there is
the combination where the data can be deleted when not used, but otherwise only deactivation is possible.

These methods can be combined according to this table: 

|             | No (de)activation | (De)activation                                 | 
|-------------|-------------------|------------------------------------------------|
| No blocke   | Can always delete | Always (de)activation                          |
| Usage block | Blocked when used | (De)activation when in use, otherwise deletion |

To add (de)activation to a model, assign the `ActivatingYesNoType` to a field or set `activatingValue` and 
`deactivatingValue` for a field. If this is set the deletion snippets will see that (de)activation should be used
instead of deletion. This is all standartd functionality in **zalt-model**.

**GemsTracker** classes add the option to add use counting to handlers by adding `UsageCounterInterface` objects
as `usageCounter` properties to the handler. Using extensions of the `UsageCounterBasic` object it is easy to 
check for use of a value in other tables.

In both cases the (menu) labels are determined by dependencies.

When a `usageCounter` property exists in a handler a `UsageDependency` is automatically added to the model. I.e.: 
you do not need to add it to the model yourself.

When no `usageCounter` property exists you should manually add an `ActivationDependency` to the model. As both
dependencies use the `MenuSnippetHelper` object, which cannot be loaded with normal constructor dependency injection
as they are created in the  `GemsSnippetResponder` class, this requires some extra code in de the handler:
```
if ($detailed) {
    if ($this->responder instanceof GemsSnippetResponder) {
        $menuHelper = $this->responder->getMenuSnippetHelper();
    } else {
        $menuHelper = null;
    }
    $metaModel = $model->getMetaModel();
    $metaModel->addDependency(new Model\Dependency\ActivationDependency(
    $this->translate,
    $metaModel,
    $menuHelper,
));
}
```

The `MenuSnippetHelper` may be null, but is just for ease of coding. The `ActivationDependency` only  does something 
with the menu.
