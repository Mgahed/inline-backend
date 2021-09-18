# BackEnd Documentation

## Register

End point: https://inline.mrtechnawy.com/api/auth/register

Method: <span style="color:red;">post</span>

body: 

    "name": "John Doe",
    "email": "example@mail.com",
    "password": "your password",
    "password_confirmation": "your password",
    "phone_number": "your number",
    "date_of_birth": "Y-m-d"


header

    Content-Type : application/json
    Accept : application/json


### Response should be

    "status": true,
    "message": "User successfully registered",
    "user": {
        "name": "John Doe",
        "email": "example@mail.com",
        "phone_number": "your number",
        "date_of_birth": "Y-m-d"
        "updated_at": "time",
        "created_at": "time",
        "id": 1
    }

------------------------------------------------
------------------------------------------------

## Login

End point: https://inline.mrtechnawy.com/api/auth/login

Method: <span style="color:red;">post</span>

body:

    "username": "your email or phone number",
    "password": "your password",


header

    Content-Type : application/json
    Accept : application/json


### response should be

    "status": true,
    "access_token": "Token",  //This token should be rememberd with frontend
    "token_type": "bearer",
    "expires_in": 3600,
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "example@mail.com",
        "email_verified_at": null,
        "phone_number": "your number",
        "date_of_birth": "Y-m-d"
        "updated_at": "time",
        "created_at": "time",
        "user_role": "normal"
    }

------------------------------------------------
------------------------------------------------

## User Profile

End point: https://inline.mrtechnawy.com/api/auth/user-profile

Method: <span style="color:red;">get</span>

body: 

    No body


header

    Content-Type : application/json
    Accept : application/json
    Authorization: Bearer <Token>

### Response should be

    "status": true,
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "example@mail.com",
        "email_verified_at": null,
        "phone_number": "your number",
        "date_of_birth": "Y-m-d"
        "updated_at": "time",
        "created_at": "time",
        "user_role": "normal"
    }

------------------------------------------------
------------------------------------------------

## Log Out

End point: https://inline.mrtechnawy.com/api/auth/logout

Method: <span style="color:red;">post</span>

body:

    No body

header

    Content-Type : application/json
    Accept : application/json
    Authorization: Bearer <Token>

### Response should be

    "status": true,
    "message": "User successfully signed out"
