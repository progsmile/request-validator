# PHP Easy Validators

Easy validation for popular PHP Frameworks, CMS/CMF (in development)

## Systems support
- [x] Phalcon
- [x] WordPress

## Available rules
- [x]  accepted
- [x]  alpha
- [x]  between
- [x]  boolean
- [x]  email
- [x]  json
- [x]  image
- [x]  ip
- [x]  max
- [x]  min
- [x]  numeric
- [x]  required
- [x]  same
- [x]  unique
- [x]  url

### Examples
```php
# Create new Validator, pass data, define rules and custom messages
# Also has errors messages by default
$validator = Validator::make($_POST, [
   'firstname' => 'required|min:2|max:14',
   'lastname'  => 'required|min:2|max:30',
   'email'     => 'required|email|unique:users',
   'age'       => 'required|numeric|min:18',
   'password'  => 'required|min:6',
   'rule'      => 'accepted',
   'website'   => 'url'
], [
   'email.required'     => 'Email is required',
   'email.email'        => 'Email has bad format',
   'email.unique'       => 'Email is not unique',
]);
```


### Advanced Usage
----

#### Setup your ORM system and use
```php
use Progsmile\Validator\DbProviders\PhalconORM;

Validator::setupDbProvider(PhalconORM::class); // Phalcon ORM comes from the box

$validator = Validator::make($this->request->getPost(), [
    'password'        => 'min:6',
    'password_repeat' => 'same:password',
    'json'            => 'json'
]);


```


#### Rules - make your own class that will help you to validate data.

```php
use Progsmile\Validator\Rules\BaseRule;

class ArraySuccessCheck extends BaseRule
{
    public function isValid()
    {
        $field = $this->params[0];
        $value = $this->params[1];
        $table = $this->params[2];

        return isset($value['success']);
    }

    public function getMessage()
    {
        return "Array :field: must have an index 'success'"; # you can use ':value:' too
    }
}
```

Now you've created your own class, inject this class to the Validator class

```php
# Just call injectClass() function
$instance->injectClass(ArraySuccessCheck::class);

$validator = $instance->make();

if ($validator->isValid() === false) {
    echo $validator->format();
}
```

#### Formatting - the best way to auto-reformat the returned array into your own style

The `$validator->format()`, by default, the messages will be formatted to html `<ul><li></li>...</ul>` element.

You can create your own class to format the array `$validator->messages()` into a well formed result.

```php
use Progsmile\Validator\Contracts\Format\FormatInterface;

class MarkdownFormatter implements FormatInterface
{
    public function reformat($messages)
    {
        $ret = '#### Error Found';

        foreach ($messages as $field => $message) {

            foreach ($message as $content) {

                $ret .= "- [x] ".$content."**\n"
            }
        }

        return $ret;
    }
}
```

Then in to use this call, you must do this way:

```php
$validator = Validator::make(
    # ... some code here...
);

echo $validator->format(MarkdownFormatter::class);
```

----

Project is just started and it is not stable yet, we love to have your fork requests.
