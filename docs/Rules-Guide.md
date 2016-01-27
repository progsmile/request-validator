## Rules list
 * Alpha               
 * Between            
 * Boolean            
 * Date and Time Format       
 * Email          
 * Exists       
 * Json          
 * In                
 * Image           
 * Ip 
 * Max 
 * Min 
 * Not in 
 * Numeric 
 * Phone Mask 
 * Required 
 * Same 
 * Unique 
 * Url

### Alpha
Checks if current string contains only letters
```php
'firstname' => 'alpha' // in rules

'firstname' => 'Denis '       //true because of auto trim 
'firstname' => 'Abra Cadabra' //false
'firstname' => ''             //true, because not required
'firstname' => ' '            //false, because empty value

```

### Between
Checks if value is between set
```php
'randNum'   => 'between:1,50' // in rules

'randNum' => '1'  //true
'randNum' => '50' //true
'randNum' => '51' //false

```

### Boolean
Applies only true or false values
```php
'randNum' => 'boolean' // in rules

'randNum' => 'true'  //true
'randNum' => 'false' //true
'randNum' => '123'   //false
```


### Date and Time Custom Format
Applies if user value matches datetime pattern
```php
'yearHours' => 'dateFormat:(Y H)' // in rules

'yearHours' => '2012 23'  //true
'yearHours' => '2012-23'  //false
'yearHours' => '1888 00'  //true
```

### Email
Matches email address
```php
'email' => 'email' // in rules

'email' => 'test@email.com'      //true
'email' => 'test-failed @su.re'  //false
```


### Exists
Searches for record in table, true if exists
```php

'email' => 'exists:users'  // email - attribute, users - table

'email' => 'bob@matros.com'   //true
'email' => 'boby@matros.com'  //false
```

### Json
Matches string is Json
```php
'response' => 'json'  // in rules

'response' => '[{}]'            //true
'response' => '[(\/)O_o{\/}]'   //false
'response' => '{"require": {"php": ">=5.4"} }' //true
```

### In
Checks if value exists in array
```php
'shop' => 'in:Metro, ATB, Silpo'  // in rules

'shop' => 'Metro'     //true
'shop' => 'McDonalds' //false
```


### Image
If file is image, it works)
```php
'myPhoto' => 'image'  // in rules
```


### Ip
Check ipv4 or ipv6 for validness
```php
'ipAddr' => 'ip'  // in rules

'ipAddr' => '77.132.104.66'        //true
'ipAddr' => '77.132.104~66'        //false
'ipAddr' => '2607:f0d0:1002:51::4' //true
```


### Max
Checking for string length or value of number less than param 
```php
'str' => 'max:5'         // string length
'num' => 'max:5|numeric' // for numeric values

'str' => 'hello'  //true
'str' => 'world!' //false

'num' => '3'      //true
'num' => '-100'   //true
'num' => '7'      //false
```


### Min
Checking for string length or value of number less than param 
```php
'str' => 'min:2'         // string length
'num' => 'min:2|numeric' // for numeric values

'str' => 'hello'  //true
'str' => 'w'      //false

'num' => '2'      //true
'num' => '-100'   //false
'num' => '7'      //true
```


### Not In
Checks if value not exists in array
```php
'shop' => 'notIn:Metro, ATB, Silpo'  // in rules

'shop' => 'Metro'     //false
'shop' => 'McDonalds' //true
```


### Numeric
Checks if value is number
```php
'age' => 'numeric'  // in rules

'age' => '100'    //true
'age' => '100kb'  //false
```


### Phone Mask
Checking if value matches phone mask
```php
'phone' => 'phoneMask:(+38(###)###-##-##)', //define phone mask

'phone' => '+38(052)123-45-67'  //true
'phone' => '+38-052-123-45-67'  //false
'phone' => 'phone test fails'   //false
```


### Required
Checks for field is required and not empty
```php
'value' => 'required'  // in rules

'value' => 'yep' //true
'value' => ' '   //false
'value' => ''    //false
```

### Same
Check if one field has same value with other
```php
'password_repeat' => 'same:password'  // in rules

'password'        => '123'  
'password_repeat' => '123' //true

'password'        => '321'  
'password_repeat' => ''    //false
```

### Unique
Checks if value is unique in table
```php
'email' => 'unique:users'  // email - attribute, users - table

'email' => 'john@doe.com' //true
'email' => 'bob@ubi.mmx'  //false
```

### Url
Matches url pattern
```php
'site' => 'url'  // in rules

'site' => 'https://mmx.com' //true
'site' => 'mmx.com'         //true
'site' => 'mmx'             //false
```
