# Laravel RESTful API - Attendance API

This is a RESTful API built for an attendance management system

## Architecture
- MVC (Model-View-Controller) — Laravel's default structure to separate business logic, presentation, and data handling.

- Service Pattern — Encapsulates business logic into dedicated service classes for better maintainability and testability.

- Request Validation (Default Directory) — Uses Laravel's App\Http\Requests for input validation to keep controllers clean and robust.

- Resource Response (Default Directory) — Leverages Laravel's App\Http\Resources to format and structure JSON responses consistently.

- Policy (Authorization Handling) — Applies Laravel's policy classes to manage fine-grained authorization logic on models.

- Integration Testing — Ensures feature functionality using Laravel’s built-in test suite via PHPUnit.


## Requirement

- Framework **Laravel** ✓
- Database **PostgreSQL** ✓
- API token using **Bearer** (Library Laravel Sanctum) ✓
- Password hashing (Driver **bcrypt**) ✓
- Login (Generate token) ✓
- Insert attendance (Token validation) ✓
- Get data attendance (Token validation) ✓
- Approve attendance by their supervisor only (Token validation) ✓

## Features
### User Features
- Login
- View their attendance List
- Submit attendance (Max 2 each day, 1 Check In and 1 Check Out)
### Supervisor Features
- Login
- View attendance list under their supervision
- Approve attendance (Only their supervised users)

## Tech Stack

- PHP 8.x
- Laravel 9.x
- PostgreSQL
- Composer

## ⚙️ Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/umars28/attendance-api.git
   cd attendance-api

2. Install dependencies
    ```bash
    composer install
3. Copy .env file
    ```bash
    cp .env.example .env
    cp .env.example.testing .env.testing
4. Configure environment variables
    ```bash
    .env
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=yourdatabase
    DB_USERNAME=yourpostgresusername
    DB_PASSWORD=yourpostgrespassword

    .env.testing (for testing unit test)
    APP_ENV=testing
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=usingdatabasedifferent
    DB_USERNAME=yourpostgresusername
    DB_PASSWORD=yourpostgrespassword
5. Generate application key
    ```bash
    php artisan key:generate
    php artisan key:generate --env=testing
6. Run database migrations
    ```bash
    php artisan migrate
7. Run seeder
    ```bash
    php artisan db:seed
8. Run unit/integration test (optional)
    ```bash
    php artisan test
9. Serve the application
    ```bash
    php artisan serve
10. Test and Run API
    ```bash
    Run API based on route endpoint URL or can import file Attendance_API.postman_collection on root folder inside postman or other tools.

## ⚙️ API

#### 1. Login  
**POST** `api/login`

**Description:**  
Authenticate a user and retrieve an access token to authorize future requests.

**Request Headers:**
- `Content-Type: application/json`

**Request Body Example:**
```json
{
  "email": "umar@gmail.com",
  "password": "password"
}
```

**Response Success Example:**
```json
{
  "status": "success",
  "message": "Authentication successful",
  "data": {
    "token": "14|hm67JxQpon5OgRRAgRbFxDtrcBxqqYvjKXKmPjlTaff279c8",
    "user": {
      "id": 3,
      "nama": "Supervisor 1",
      "email": "spv1@gmail.com",
      "npp": "11111",
      "npp_supervisor": null
    }
  }
}
```
**Response Error Validation Example:**
```json
{
    "status": "error",
    "message": "The provided data is invalid",
    "errors": {
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```
**Response Error Invalid Credential Example:**
```json
{
    "status": "error",
    "message": "Invalid credentials"
}
```


#### 2. Submit Attendance  
**POST** `api/epresence`

**Description:**  
Submit an attendance entry for the currently authenticated user.  
This is used to record a clock-in or clock-out action and requires an Authorization Bearer Token.

**Request Headers:**
- `Content-Type: application/json`
- `Authorization: Bearer {access_token}`

**Request Body Example:**
```json
{
  "type": "IN",
  "waktu": "2025-04-09 22:59:13"
}
```

**Response Success Example:**
```json
{
    "status": "success",
    "message": "Record created successfully",
    "data": {
        "id": 12,
        "type": "IN",
        "waktu": "2025-04-09 22:59:13",
        "is_approve": false
    }
}
```
**Response Validation Example:**
```json
{
    "status": "error",
    "message": "The provided data is invalid",
    "errors": {
        "type": [
            "The type field is required."
        ],
        "waktu": [
            "The waktu field is required."
        ]
    }
}
```

**Response Validation Example:**
```json
{
    "status": "error",
    "message": "The provided data is invalid",
    "errors": {
        "waktu": [
            "You have already performed absensi masuk on the selected date."
        ]
    }
}
```
**Response Unauthorized Example:**
```json
{
    "status": "error",
    "message": "You are not authorized to perform this action"
}
```

#### 3. List Attendance  
**GET** `api/epresence`

**Description:**  
Retrieve a list of attendance records for the authenticated user.

**Request Headers:**
- `Content-Type: application/json`
- `Authorization: Bearer {access_token}`

**Request Params Example:**
```bash
api/epresence
api/epresence?limit=5
api/epresence?page=2
api/epresence?limit=5&page=2
```

**Response Success Supervisor Example:**
```json
{
    "status": "success",
    "message": "Success get data",
    "data": [
        {
            "id_user": 1,
            "nama_user": "Ananda Bayu",
            "tanggal": "2025-05-23",
            "waktu_masuk": "22:09:13",
            "waktu_pulang": "22:09:13",
            "status_masuk": "REJECT",
            "status_pulang": "APPROVE"
        },
        {
            "id_user": 2,
            "nama_user": "Umar",
            "tanggal": "2025-05-23",
            "waktu_masuk": "22:59:13",
            "waktu_pulang": "-",
            "status_masuk": "REJECT",
            "status_pulang": "-"
        },
        {
            "id_user": 1,
            "nama_user": "Ananda Bayu",
            "tanggal": "2025-05-22",
            "waktu_masuk": "07:09:13",
            "waktu_pulang": "17:09:13",
            "status_masuk": "REJECT",
            "status_pulang": "APPROVE"
        }
    ]
}
```
**Response Success User Example:**
```json
{
    "status": "success",
    "message": "Success get data",
    "data": [
        {
            "id_user": 2,
            "nama_user": "Umar",
            "tanggal": "2025-05-23",
            "waktu_masuk": "22:59:13",
            "waktu_pulang": "-",
            "status_masuk": "REJECT",
            "status_pulang": "-"
        },
        {
            "id_user": 2,
            "nama_user": "Umar",
            "tanggal": "2025-05-22",
            "waktu_masuk": "-",
            "waktu_pulang": "22:59:13",
            "status_masuk": "-",
            "status_pulang": "REJECT"
        },
        {
            "id_user": 2,
            "nama_user": "Umar",
            "tanggal": "2025-05-21",
            "waktu_masuk": "07:59:13",
            "waktu_pulang": "07:59:13",
            "status_masuk": "REJECT",
            "status_pulang": "REJECT"
        }
    ]
}
```


#### 4. Approve Attendance  
**PATCH** `api/epresence/{id}/approve`

**Description:**  
Approve an attendance record by ID. Typically used by a supervisor to approve a subordinate's attendance.

**Request Headers:**
- `Content-Type: application/json`
- `Authorization: Bearer {access_token}`

**Response Success Example:**
```json
{
    "status": "success",
    "message": "Presence has been approved successfully",
    "data": {
        "id": 3,
        "type": "OUT",
        "waktu": "2025-05-22 17:09:13",
        "is_approve": true
    }
}
```
**Response Unauthorized Example:**
```json
{
    "status": "error",
    "message": "You are not authorized to approve this record"
}
```