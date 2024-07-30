# Demo-API-Python-Application

A demo showcasing a Kivy Python app and a RESTful API for educational purposes. Includes user management, transactions, and logging functionalities. Explore the live demo and learn. Ideal for learning API integration and backend development.

## Overview

This repository provides a demonstration of a RESTful API and a Kivy-based Python application designed to showcase how to integrate and interact with APIs. The demo application, which is available [live here](https://zofasoftwares.com/demo){:target="_blank"}, serves as an educational tool for understanding API integration and basic application development.

## Live Demo Api

You can explore the live demo of the application at: [https://zofasoftwares.com/demo](https://zofasoftwares.com/demo){:target="_blank"}

### Authentication Token

To interact with the API, use the authentication token provided below:

```
3b408cb48548b5037822c10eb0976b3cbf2cee3bf9c708796bf03941fbecd80f
```

## Demo Application Built On The Live Api

For a hands-on experience, download and run the demo application executable from the following link:

[Download Demo App (EXE)](https://raw.githubusercontent.com/omerktk/Demo-API-Python-Application/main/app_on_api/build/demo_app_live_working.exe){:target="_blank"}

## API Documentation

The API URL is: `http://localhost/demo/api`

### Authentication

- **Endpoint:** `api/auth/`
- **Method:** POST
- **Request JSON:** `{"token": "your_token_here"}`
- **Success Response:** `{"status": "success"}`

### Users

- **Create User:** `api/users/create/` (POST)
- **Read Users:** `api/users/read/` (POST)
- **Update User:** `api/users/update/` (POST)
- **Delete User:** `api/users/delete/` (POST)
- **Find User by Token:** `api/users/findtoken/` (POST)

### Transactions

- **Create Transaction:** `api/transactions/create/` (POST)
- **Read Transactions:** `api/transactions/read/` (POST)
- **Update Transaction:** `api/transactions/update/` (POST)
- **Delete Transaction:** `api/transactions/delete/` (POST)

### Logs

- **Create Log:** `api/logs/create/` (POST)
- **Read Logs:** `api/logs/read/` (POST)

## Local Setup

### PHP & MySQL Version

For those interested in a PHP and MySQL implementation, use the provided API documentation to set up a local version of the API.

### Python Application

1. Clone the repository.
2. Install required dependencies using:
    ```bash
    pip install -r requirements.txt
    ```
3. Run the Kivy application:
    ```bash
    python app.py
    ```

## How to Use

- **Authentication:** Generate your token by concatenating username and password with '@' and hashing the result using SHA-256.
- **API Calls:** Use the endpoints provided to interact with the API for user management, transactions, and logging.

## Contribution

Feel free to fork this repository, contribute, and submit pull requests. Your feedback and improvements are welcome!

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE){:target="_blank"} file for details.

## Contact

For any questions or support, please contact [omektk876@gmail.com](mailto:omektk876@gmail.com){:target="_blank"}.

---

This project is intended for educational purposes and demonstrates basic API and application integration concepts. For more detailed implementation, refer to the provided API documentation and application code.
