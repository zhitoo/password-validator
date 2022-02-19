# password-validator
password validator tools for laravel

```
composer require hshafiei374/password-validator
```
- have_strength
- have_uppercase
- have_lowercase
- have_number
- have_symbol

##have_strength
```
$validateInputs = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|confirmed|min:6|have_strength:2'
        ]);
        
```
we can set have_strength from 1 to 5 and by default set on 5
```
$validateInputs = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|confirmed|min:6|have_strength'//set on 5
        ]);
```
- base_rule: password must at least has {number} chars default: number=6 
- rule 1 and at least has one a-z chars or lowercase
- rule 2 and at least has one A-Z       or lowercase-uppercase  
- rule 3 and at least has one 0-9       or lowercase-uppercase-number
- rule 4 and at least has one special chars like @ $ ! % * # ? & or lowercase-uppercase-number-symbol

by default password length is 6 but you can change it.
```
$validateInputs = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|confirmed|min:6|have_strength:,8'//password length at least 8
        ]);
```

set both strong and password chars length 

```
$validateInputs = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|confirmed|min:6|have_strength:4,8'//first is strong and second is password length
        ]);
```

###if you want use only special characters or only uppercase ...
```
$validateInputs = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|confirmed|min:6|have_strength:uppercase,8'//first is strong and second is password length
        ]);
```
```
$validateInputs = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|confirmed|min:6|have_strength:symbol-uppercase,8'//first is strong and second is password length
        ]);
```

###you can use each rule separately:
##have_number
```
$validateInputs = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|confirmed|min:6|have_number:3'//password must have 3 numeric characters
        ]);
```
##have_symbol
```
$validateInputs = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|confirmed|min:6|have_symbol'//password must have 1 special character
        ]);
```
##have_uppercase
```
$validateInputs = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|confirmed|min:6|have_uppercase'//password must have 1 uppercase character
        ]);
```
##have_lowercase
```
$validateInputs = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|confirmed|min:6|have_lowercase:2'//password must have 2 lowercase character
        ]);
```
