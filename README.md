# Healthcare Appointment Booking System

A **production-ready RESTful API** built with **Laravel 11** to manage healthcare appointments. Includes robust authentication, validation, business logic, and a scalable architecture for future enhancements.

---

## Features

- **User Authentication**: Token-based authentication using Laravel Sanctum
- **Healthcare Professional Management**: List and filter available professionals
- **Appointment Booking**: Book appointments with availability checking
- **Appointment Management**: View, cancel, and complete appointments
- **Business Rules**:
  - Prevent double booking
  - 24-hour cancellation policy
  - Future date validation
  - User authorization checks

---

## Tech Stack

| Layer           | Technology            |
|-----------------|---------------------|
| Framework       | Laravel 11          |
| Database        | MySQL               |
| Authentication  | Laravel Sanctum     |
| Testing         | PHPUnit             |
| Architecture    | Repository-Service Pattern |

---

## Requirements

- PHP >= 8.2  
- Composer  
- MySQL >= 8.0  

---

## Installation

### 1. Clone Repository

```bash
git clone https://github.com/vivek10688/laravel-healthcare-api.git
cd laravel-healthcare-api


### 2. Install Dependencies

composer install

### 3. Environment Setup

cp .env.example .env
php artisan key:generate

Update `.env` with your database credentials:

DB_CONNECTION=mysql
DB_HOST=your_db_host
DB_PORT=your_db_port
DB_DATABASE=your_db_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

### 4. Database Setup

php artisan migrate
php artisan db:seed


### 5. Run Application

php artisan serve

API will be available at `http://localhost:8000` or `http://127.0.0.1:8000`

## API Documentation

### Base URL

http://localhost:8000/api or http://127.0.0.1:8000/api


| Endpoint         | Method | Description             |
| ---------------- | ------ | ----------------------- |
| `/auth/register` | POST   | Register a new user     |
| `/auth/login`    | POST   | Login and get API token |
| `/auth/logout`   | POST   | Logout user             |



### User Authentication

#### Register User API

POST /api/auth/register
Content-Type: application/json

## Request Params

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "Password",
    "password_confirmation": "Password"
}

#### User Login API

POST /api/auth/login
Content-Type: application/json

## Request Params

{
    "email": "john@example.com",
    "password": "Password"
}

Response:

{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "token": "x|xxx..."
    }
}

#### Logout User API

POST /api/auth/logout
Authorization: Bearer {token}




### Healthcare Professionals Related Api's List

#### List All Healthcare Professionals

| Endpoint                    | Method | Description                       |
| --------------------------- | ------ | --------------------------------- |
| `/healthcare-professionals` | GET    | List all healthcare professionals |

## API Format

GET /api/healthcare-professionals
Authorization: Bearer{token}


### Appointments Related Api's List


| Endpoint                      | Method | Description                  |
| ----------------------------- | ------ | ---------------------------- |
| `/appointments`               | POST   | Book appointment             |
| `/appointments`               | GET    | View all user appointments   |
| `/appointments/{id}`          | GET    | View specific appointment    |
| `/appointments/{id}/cancel`   | PATCH  | Cancel appointment           |
| `/appointments/{id}/complete` | PATCH  | Mark appointment as complete |


#### Book Appointment

POST /api/appointments
Authorization: Bearer{token}
Content-Type: application/json

{
  "healthcare_professional_id": 1,
  "appointment_start_time": "2025-10-09T12:00:00Z",
  "appointment_end_time": "2025-10-09T12:30:00Z",
  "notes": "Annual checkup"
}

#### View User Appointments

GET /api/appointments
Authorization: Bearer {token}

#### View Specific Appointment

GET /api/appointments/{id}
Authorization: Bearer{token}

#### Cancel Appointment

PATCH /api/appointments/{id}/cancel
Authorization: Bearer {token}
Content-Type: application/json

{
  "cancellation_reason": "Schedule conflict"
}

#### Complete Appointment

PATCH /api/appointments/{id}/complete
Authorization: Bearer{token}

## Testing

Run all tests:

php artisan test

Run specific test suite:

php artisan test --filter AuthenticationTest

With coverage (requires Xdebug):

php artisan test --coverage

## Error Handling

All errors return consistent JSON responses:

{
	"success": false,
	"message": "Error message",
	"errors": {
		"field": ["validation error"]
	}
}


HTTP Status Codes:
- `200`: Success
- `201`: Created
- `400`: Bad Request
- `401`: Unauthorized
- `403`: Forbidden
- `404`: Not Found
- `422`: Validation Error
- `500`: Server Error




## Testing Coverage

Current test coverage includes:
- User Authentication (register, login, logout)
- Validation Appointment booking 
- Appointment cancellation with 24-hour
- Authorization checks
- Healthcare professional listing
