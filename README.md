# IT Support Request Portal

> A web-based IT support ticket management system built with Core PHP and MySQL.

## 📌 About the Project

IT Support Request Portal is a role-based web application designed to simplify the process of reporting and managing IT support requests.

Employees can submit support tickets and track their status, while administrators can view, filter, and resolve submitted requests.

The project was built from scratch with a focus on backend development, authentication, authorization, and secure server-side processing.

## ✨ Features

### 👤 Employee

- Secure registration and login
- Employee dashboard
- Submit IT support requests
- View submitted tickets
- View ticket details
- Track ticket status

### 🛠️ Admin

- Secure admin authentication
- Admin dashboard
- View ticket statistics
- View all support requests
- Filter tickets by status
- View complete ticket details
- Resolve open tickets

### 🔐 Security

- Password hashing
- Prepared SQL statements
- CSRF protection
- Role-based authorization
- Secure session configuration
- Session timeout
- Login attempt protection
- Server-side validation
- IDOR protection
- Security headers
- Centralized error handling

## 🧰 Tech Stack

| Technology | Usage |
|---|---|
| HTML5 | Structure |
| CSS3 | Styling |
| JavaScript | Client-side functionality |
| PHP | Backend |
| MySQL | Database |
| XAMPP | Local development |
| Git & GitHub | Version control |

## 📂 Project Structure

```text
IT_requestportalv2/
│
├── admin/
│   ├── dashboard.php
│   └── resolve-ticket.php
│
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── auth/
│   ├── login.php
│   ├── logout.php
│   └── signup.php
│
├── config/
│   └── db.php
│
├── employee/
│   ├── dashboard.php
│   ├── create-ticket.php
│   ├── tickets.php
│   └── ticket.php
│
├── includes/
│   ├── session.php
│   ├── auth.php
│   └── error-handler.php
│
└── index.php
```

## 🔄 Ticket Workflow

```text
Employee
   │
   ▼
Submit Support Request
   │
   ▼
Ticket Created
   │
   ▼
Status: Open
   │
   ▼
Admin Reviews Ticket
   │
   ▼
Admin Resolves Ticket
   │
   ▼
Status: Resolved 
```

## 🗄️ Database

The application uses MySQL with the following main tables:

### Users

Stores employee and administrator account information.

### Tickets

Stores IT support requests submitted by employees.

### Login Attempts

Stores login attempt information used for brute-force protection.


## 📌 Project Status

The core functionality and backend security implementation are complete.

The project is currently being refined for its final presentation and mobile user interface.

## 👨‍💻 Author

**Arya Patil**

Web Developer

GitHub: https://github.com/web-aryacodes

LinkedIn: https://www.linkedin.com/in/arya-patil3455/



