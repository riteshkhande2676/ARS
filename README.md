# ARS ENGINEERS - Solar Energy Website (Java Backend)

A premium, full-featured solar energy website with a **Java Spring Boot** backend and MySQL database.

## 🌟 Features

- ✨ **Design**: Modern green-themed responsive design
- 🔧 **Backend**: Java 17+ with Spring Boot 3.2
- 🗄️ **Database**: MySQL 8.0+
- 📝 **API**: RESTful API for contact submissions and admin management
- 📊 **Admin Panel**: Static HTML/JS frontend consuming the Java API

## 📋 Requirements

- **Java Development Kit (JDK) 17** or higher
- **Maven 3.6+**
- **MySQL 8.0+**

## 🚀 Installation & Running

### 1. Database Setup

Ensure MySQL is running and the database exists:

```sql
CREATE DATABASE IF NOT EXISTS ars_solar;
```

The application is configured to use `root` user with empty password by default.
Update `src/main/resources/application.properties` if your credentials differ.

### 2. Build the Application

```bash
mvn clean install
```

### 3. Run the Application

```bash
mvn spring-boot:run
```

The server will start on port **8080**.

### 4. Access

- **Website**: http://localhost:8080
- **Admin Panel**: http://localhost:8080/admin.html

## 📁 Project Structure

```
src/main/java/com/arsengineers/solar/
├── SolarApplication.java       # Main entry point
├── controller/                 # REST Controllers
├── model/                      # JPA Entities
└── repository/                 # Data Repositories

src/main/resources/
├── application.properties      # Config
└── static/                     # Frontend files
    ├── index.html
    ├── admin.html
    ├── styles.css
    ├── script.js
    └── ...
```

## 🔒 Security Note

This project is configured for development. For production:
- Update database credentials in `application.properties`.
- Enable Spring Security.
- Set up HTTPS.
