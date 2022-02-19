# password-validator
password validator tools for laravel
```
composer require hshafiei374/password-validator
```
```
$validateInputs = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|confirmed|min:6|password_strength:2'
        ]);
        
```
we can set password_strength from 1 to 5 and by default set on 5
```
$validateInputs = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|confirmed|min:6|password_strength'//set on 5
        ]);
```
- 1 password must at least has 6 chars
- 2 1 and at least has one a-z chars
- 3 2 and at least has one A-Z 
- 4 3 and at least has one 0-9
- 5 4 and at least has one special chars like @ $ ! % * # ? &

by default password length is 6 but you can set it.
```
$validateInputs = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|confirmed|min:6|password_strength:8'//password length at least 8
        ]);
```

set both strong and password chars length 

```
$validateInputs = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|confirmed|min:6|password_strength:4,8'//first is strong and second is password length
        ]);
```
