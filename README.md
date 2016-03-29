# PHP Request Validator

Make your apps validation easily (inspired by Laravel Validation)

[![Build Status](https://travis-ci.org/progsmile/request-validator.svg?branch=master)](http://travis-ci.org/progsmile/request-validator)  [![Total Downloads](https://poser.pugx.org/progsmile/request-validator/d/total)](https://packagist.org/packages/progsmile/request-validator) [![License](https://poser.pugx.org/progsmile/request-validator/license.svg)](https://packagist.org/packages/progsmile/request-validator)

---

Page Index:
- [Quick Start](#quick-start)
- [Contributing](#contributing)
- [Testing](#testing)
- [License](#license)

Suggested Links:
- [Installation](/docs/installation.md)
- [Feature Guide](/docs/feature-guide.md)
- Rules
    - [Existing Rules](/docs/rules.md)
    - [Data Provider](/docs/rules-database.md)
    - [Customization](/docs/rules-customization.md)
    - [Formatting Message](/docs/formatting-message.md)
- [Integrations](/docs/integrations.md)

----

<a name="quick-start"></a>
## Quick start :rocket:
```php
<?php

$rules = [
    # firstname and lastname must exists
    # they should be alphanumeric
    # atleast 2 characters
    'firstname, lastname' => 'required|alpha|min:2',

    # max until 18 characters only
    'lastname'            => 'max:18',

    # must be an email format
    # must be unique under 'users' table
    'email'               => 'email|unique:users',

    # must be numeric
    # must exists under 'users' table
    'id'                  => 'numeric|exists:users',
    'age'                 => 'min:16|numeric',
    'info[country]'       => 'required|alpha',

    # roll[0] or roll[1] values must be in the middle 1 to 100
    'roll[0], roll[1]'    => 'numeric|between:1, 100',

    # the format must be 'm-Y.d H:i'
    'date'                => 'dateFormat:(m-Y.d H:i)',

    # it must be an image format under $_FILES global variable
    'profileImg'          => 'image',

    # the provided phone number should follow the format
    # correct: +38(123)456-12-34
    # wrong: +38(123)56-123-56
    # wrong: +39(123)456-12-34
    'phoneMask'           => 'phoneMask:(+38(###)###-##-##)',
    'randNum'             => 'between:1, 10|numeric',

    # the value must be an IP Format
    'ip'                  => 'ip',
    'password'            => 'required|min:6',

    # the value from a key 'password' must be equal to 'password_repeat' value
    'password_repeat'     => 'same:password',

    # it must be a json format
    'json'                => 'json',
    'site'                => 'url',

    # cash10 or cash25 must only have these
    # 1 or 2 or 5 or 10 or 20 or 50
    'cash10, cash25'      => 'in:1, 2, 5, 10, 20, 50',

    # the value must not have 13 or 18 or 3 or 4
    'elevatorFloor'       => 'notIn:13, 18, 3, 4',
];

$customMessage = [
   'info[country].alpha' => 'Only letters please',
   'email.required'      => 'Field :field: is required',
   'email.email'         => 'Email has bad format',
   'email.unique'        => 'This email :value: is not unique',
   'elevatorFloor.notIn' => 'Oops',
];

$v = V::make($_POST, $rules, $customMessage);

# for specific field
# you can use below code.
$v->lastname->passes();
$v->lastname->fails();

# if you're required to check everything
# and there must no failing validation
$v->passes();
$v->fails();

# get first error message
$v->first();

# get first error for `firstname`
$v->first('lastname');
$v->firstname->first();

# return first error message from each field
$v->firsts();

# get all messages (with param for concrete field)
$v->messages();
$v->messages('password');

# get all `password` messages
$v->password->messages();

# get 2d array with fields and messages
$v->raw();

# to append a message
$v->add('someField', 'Something is wrong with this');
```

<a name="contributing"></a>
## Contributing :octocat:

Dear contributors , the project is just started and it is not stable yet, we love to have your fork requests.


<a name="testing"></a>
## Testing

This testing suite uses [Travis CI](https://travis-ci.org/) for each run. Every commit pushed to this repository will queue a build into the continuous integration service and will run all tests to ensure that everything is going well and the project is stable.

The testing suite can be run on your own machine. The main dependency is [PHPUnit](https://github.com/sebastianbergmann/phpunit) which can be installed using [Composer](http://getcomposer.org):

```sh
# run this command from project root
$ composer install --dev --prefer-source
```

A MySQL database is also required for several tests. Follow these instructions to create the database:

```sh
echo 'create database valid charset=utf8mb4 collate=utf8mb4_unicode_ci;' | mysql -u root
cat tests/dist/schema.sql | mysql valid -u root
```

For these tests we use the user `root` without a password. You may need to change this in `tests/TestHelper.php` file.

Once the database is created, run the tests on a terminal:

```sh
vendor/bin/phpunit --configuration phpunit.xml --coverage-text
```

For additional information see [PHPUnit The Command-Line Test Runner](http://phpunit.de/manual/current/en/textui.html).

<a name="license"></a>
## License

PHP Request Validator is open-sourced software licensed under the [GNU GPL](LICENSE).
© 2016 Denis Klimenko and <a href="https://github.com/progsmile/request-validator/graphs/contributors">all the contributors</a>.
