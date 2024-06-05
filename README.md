API Documentation
This documentation covers the RESTful API endpoints for user authentication, and bill payments using VTPass via Laravel. The API is designed for consumption by a mobile application.

Base URL
arduino
Copy code
https://usetekpay.com/api

Authentication

1) Register
Endpoint: POST /register
Description: Register a new user.

Request Body:


    {
        "name": "John Doe",
        "email": "john@example.com",
        "password": "password123",
        "password_confirmation": "password123"
    }
Response:
Success: 201 Created

    {
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
    },
        "token": "your_access_token"
   }

Error: 422 Unprocessable Entity

    {
        "message": "Validation error message"
    }

2) Login

Endpoint: POST /login
Description: Authenticate a user.
Request Body:

    {
        "email": "john@example.com",
        "password": "password123"
    }

Response:
Success: 200 OK

    {
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
    },
        "token": "your_access_token"
    }
Error: 401 Unauthorized

    {
        "message": "Invalid credentials"
    }


3) Logout

Endpoint: POST /logout
Description: Log out the authenticated user.
Headers: Authorization: Bearer your_access_token

Response:
Success: 200 OK

    {
        "message": "Successfully logged out"
    }

Error: 401 Unauthorized

    {
        "message": "Unauthorized"
    }

User Profile

1) Get Profile
Endpoint: GET /user

Description: Get the authenticated user's profile.
Headers: Authorization: Bearer your_access_token

Response:
Success: 200 OK

    {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
    }
Error: 401 Unauthorized


    {
        "message": "Unauthorized"
    }

2) Update Profile

Endpoint: PUT /user

Description: Update the authenticated user's profile.
Headers: Authorization: Bearer your_access_token
Request Body:

    {
        "name": "Jane Doe",
        "email": "jane@example.com"
    }

Response:
Success: 200 OK

    {
        "id": 1,
        "name": "Jane Doe",
        "email": "jane@example.com",
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
    }

Error: 401 Unauthorized

    {
        "message": "Unauthorized"
    }

3) Delete Profile

Endpoint: DELETE /user

Description: Delete the authenticated user's profile.
Headers: Authorization: Bearer your_access_token

Response:
Success: 204 No Content
Error: 401 Unauthorized

    {
        "message": "Unauthorized"
    }



Bill Payments

1) Buy Airtime

Endpoint: POST /pay/airtime

Description: Purchase airtime.
Headers: Authorization: Bearer your_access_token
Request Body:

    {
        "network": "mtn",
        "phone": "08012345678",
        "amount": 500
    }

Response:
Success: 200 OK

    {
        "status": "success",
        "transaction_id": "1234567890",
        "details": "Airtime purchase details..."
    }

Error: 400 Bad Request

    {
        "message": "Error message"
    }


2) Pay Electricity Bill

Endpoint: POST /pay/electricity

Description: Pay an electricity bill.
Headers: Authorization: Bearer your_access_token
Request Body:

    {
        "serviceID": "eko-electric",
        "meter_number": "1234567890",
        "amount": 1000,
        "phone": "08012345678"
    }

Response:
Success: 200 OK

    {
        "status": "success",
        "transaction_id": "1234567890",
        "details": "Electricity payment details..."
    }

Error: 400 Bad Request

    {
        "message": "Error message"
    }


3) Buy Data

Endpoint: POST /pay/data

Description: Purchase data.
Headers: Authorization: Bearer your_access_token
Request Body:

    {
        "network": "mtn",
        "phone": "08012345678",
        "amount": 1000
    }

Response:
Success: 200 OK

    {
        "status": "success",
        "transaction_id": "1234567890",
        "details": "Data purchase details..."
    }

Error: 400 Bad Request

    {
        "message": "Error message"
    }


4) Subscribe to TV

Endpoint: POST /pay/tv

Description: Subscribe to a TV service.
Headers: Authorization: Bearer your_access_token
Request Body:

    {
        "serviceID": "dstv",
        "smartcard_number": "1234567890",
        "amount": 2000,
        "phone": "08012345678"
    }

Response:
Success: 200 OK

    {
        "status": "success",
        "transaction_id": "1234567890",
        "details": "TV subscription details..."
    }

Error: 400 Bad Request

    {
        "message": "Error message"
    }

Error Handling
Common Error Responses

401 Unauthorized
    {
        "message": "Unauthorized"
    }

422 Unprocessable Entity
    {
        "message": "Validation error message"
    }

400 Bad Request
    {
        "message": "Error message"
    }






