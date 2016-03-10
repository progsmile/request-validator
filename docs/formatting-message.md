### Formatting - the best way to auto-reformat the returned array into your own style

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
$v = V::make(
    // ... some code here...
);

echo $v->format(MarkdownFormatter::class);
```
