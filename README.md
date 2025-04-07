# API Documentation (Full Docs on your http://localhost:8000/docs)

## Overview

This API is designed to manage a ticketing system, allowing users to create, update, delete, and retrieve tickets. It also provides user management functionalities, including user registration and authentication.

## Project Installation

- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate
- php artisan db:seed
- php artisan serve

OR 

- make setup

## Base URL

```
http://localhost:8000/api/v1
```

## Authentication

This API uses [Laravel Sanctum](https://laravel.com/docs/sanctum) for authentication. You need to obtain a token by logging in to access protected routes.

### Login

- **Endpoint:** `POST /login`
- **Request Body:**
  ```json
  {
    "email": "user@example.com",
    "password": "your_password"
  }
  ```
- **Response:**
  ```json
  {
    "message": "Auth successed",
    "data": {
      "token": "your_token_here"
    }
  }
  ```

### Logout

- **Endpoint:** `POST /logout`
- **Headers:**
  ```
  Authorization: Bearer your_token_here
  ```
- **Response:**
  ```json
  {
    "message": "Logout successed"
  }
  ```

## User Management

### List Users

- **Endpoint:** `GET /users`
- **Response:**
  ```json
  {
    "data": [
      {
        "type": "user",
        "id": "1",
        "attributes": {
          "name": "John Doe",
          "email": "john@example.com",
          "emailVerifiedAt": "2023-01-01T00:00:00Z",
          "createdAt": "2023-01-01T00:00:00Z",
          "updatedAt": "2023-01-01T00:00:00Z"
        }
      }
    ]
  }
  ```

### Show User

- **Endpoint:** `GET /users/{user}`
- **Response:**
  ```json
  {
    "data": {
      "type": "user",
      "id": "1",
      "attributes": {
        "name": "John Doe",
        "email": "john@example.com",
        "emailVerifiedAt": "2023-01-01T00:00:00Z",
        "createdAt": "2023-01-01T00:00:00Z",
        "updatedAt": "2023-01-01T00:00:00Z"
      },
      "includes": {
        "tickets": [
          {
            "type": "ticket",
            "id": "1"
          }
        ]
      },
      "links": {
        "self": "http://your-domain.com/api/v1/users/1"
      }
    }
  }
  ```

### Create User

- **Endpoint:** `POST /users`
- **Request Body:**
  ```json
  {
    "name": "John Doe",
    "email": "john@example.com",
    "password": "your_password"
  }
  ```
- **Response:**
  ```json
  {
    "message": "User  created successfully"
  }
  ```

### Update User

- **Endpoint:** `PATCH /users/{user}`
- **Request Body:**
  ```json
  {
    "name": "John Doe Updated"
  }
  ```
- **Response:**
  ```json
  {
    "message": "User  updated successfully"
  }
  ```

### Delete User

- **Endpoint:** `DELETE /users/{user}`
- **Response:**
  ```json
  {
    "message": "User  deleted successfully"
  }
  ```

## Ticket Management

### List Tickets

- **Endpoint:** `GET /tickets`
- **Response:**
  ```json
  {
    "data": [
      {
        "type": "ticket",
        "id": "1",
        "attributes": {
          "title": "Sample Ticket",
          "description": "This is a sample ticket.",
          "status": "Active",
          "createdAt": "2023-01-01T00:00:00Z",
          "updatedAt": "2023-01-01T00:00:00Z"
        },
        "relationships": {
          "user": {
            "data": {
              "type": "user",
              "id": "1"
            }
          }
        },
        "links": {
          "self": "http://your-domain.com/api/v1/tickets/1"
        }
      }
    ]
  }
  ```

### Show Ticket

- **Endpoint:** `GET /tickets/{ticket}`
- **Response:**
  ```json
  {
    "data": {
      "type": "ticket",
      "id": "1",
      "attributes": {
        "title": "Sample Ticket",
        "description": "This is a sample ticket.",
        "status": "Active",
        "createdAt": "2023-01-01T00:00:00Z",
        "updatedAt": "2023-01-01T00:00:00Z"
      },
      "relationships": {
        "user": {
          "data": {
            "type": "user",
            "id": "1"
          }
        }
      },
      "links": {
        "self": "http://your-domain.com/api/v1/tickets/1"
      }
    }
  }
  ```

### Create Ticket

- **Endpoint:** `POST /tickets`
- **Request Body:**
  ```json
  {
    "data": {
      "attributes": {
        "title": "New Ticket",
        "description": "Description of the new ticket.",
        "status": "Active"
      },
      "relationships": {
        "user": {
          "data": {
            "id": "1"
          }
        }
      }
    }
  }
  ```
- **Response:**
  ```json
  {
    "message": "Ticket created successfully"
  }
  ```

### Update Ticket

- **Endpoint:** `PATCH /tickets/{ticket}`
- **Request Body:**
  ```json
  {
    "data": {
      "attributes": {
        "status": "Completed"
      }
    }
  }
  ```
- **Response:**
  ```json
  {
    "message": "Ticket updated successfully"
  }
  ```

### Delete Ticket

- **Endpoint:** `DELETE /tickets/{ticket}`
- **Response:**
  ```json
  {
    "message": "Ticket deleted successfully"
  }
  ```




## User Tickets Management

### List Tickets for a User

- **Endpoint:** `GET /users/{user_id}/tickets`
- **Response:**
  ```json
  {
    "data": [
      {
        "type": "ticket",
        "id": "1",
        "attributes": {
          "title": "User 's Ticket",
          "description": "This is a ticket created by the user.",
          "status": "Active",
          "createdAt": "2023-01-01T00:00:00Z",
          "updatedAt": "2023-01-01T00:00:00Z"
        },
        "relationships": {
          "user": {
            "data": {
              "type": "user",
              "id": "1"
            }
          }
        },
        "links": {
          "self": "http://your-domain.com/api/v1/users/1/tickets/1"
        }
      }
    ]
  }
  ```

### Create Ticket for a User

- **Endpoint:** `POST /users/{user_id}/tickets`
- **Request Body:**
  ```json
  {
    "data": {
      "attributes": {
        "title": "User 's New Ticket",
        "description": "Description of the user's new ticket.",
        "status": "Active"
      }
    }
  }
  ```
- **Response:**
  ```json
  {
    "message": "User 's ticket created successfully"
  }
  ```

### Update Ticket for a User

- **Endpoint:** `PATCH /users/{user_id}/tickets/{ticket_id}`
- **Request Body:**
  ```json
  {
    "data": {
      "attributes": {
        "status": "Completed"
      }
    }
  }
  ```
- **Response:**
  ```json
  {
    "message": "User 's ticket updated successfully"
  }
  ```

### Delete Ticket for a User

- **Endpoint:** `DELETE /users/{user_id}/tickets/{ticket_id}`
- **Response:**
  ```json
  {
    "message": "User 's ticket deleted successfully"
  }
  ```

## Error Handling

The API returns standardized error responses. Here are some examples:

### 404 Not Found

```json
{
  "message": "Resource not found",
  "status": 404
}
```

### 401 Unauthorized

```json
{
  "message": "You are not authorized to access this resource",
  "status": 401
}
```

## Conclusion

This API is still under development, and additional features may be added in the future. Please refer to this documentation for the latest updates and changes. If you have any questions or feedback, feel free to reach out!