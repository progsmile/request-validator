# Phalcon Validator

Easy validation for Phalcon Framework (in development)


### Available rules
- [x]  required
- [x]  min
- [x]  unique
- [x] email

### Examples
```php
//Fetch data from request
$postData = $this->request->getPost();

//Create new Validator, pass data, define rules and custom messages
//Also has errors messages by default 


$validator = new Validator($postData, [
   'firstname' => 'required|min:2',
   'lastname'  => 'required|min:5',
   'email'     => 'required|email|unique:Users', //Multiple\Shared\Models\Users in modular app
   'password'  => 'required|min:6',
], [
   'email.required'     => 'Email is required',
   'email.email'        => 'Email has bad format',
   'email.unique'       => 'Email is not unique',
]);
```

### Unique field in models
Your model should implement IUniqueness interface


```php
//simple return unique field name
public function getUniqueFieldName()
{
    return 'email';
}

```

Project is just started and may be non stable

Appreciate contributors for help)
