## Adding custom rules
- Create class and extend custom Rule from `\Progsmile\Validator\Rules\BaseRule`
- Pass the rule to other validations

```php
$rules = [
    'realty_data' => ['json', RealtyDataRule::class]
];

$validation = Validator::make($requestData, $rules);

```
