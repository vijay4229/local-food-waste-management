# Food Waste Management System

A web-based application developed using PHP and MySQL that connects food donors with charities and NGOs to reduce food wastage. The system facilitates the donation process through three distinct modules: Admin, User (Donor), and Delivery Person.

## Features

### User (Donor) Module
- Registration and Login.
- Post food donation requests (Veg/Non-veg, Raw/Cooked).
- View donation history.

### Admin Module
- Dashboard with analytics (Total Users, Donations, Feedbacks).
- Notification Center for real-time donation alerts.
- Manage food requests and assign them to delivery personnel.
- View feedback and user profiles.

### Delivery Module
- Delivery personnel registration and login.
- View assigned orders based on location (City).
- "Take Order" functionality to accept deliveries.
- Map integration to view current location.

## Tech Stack
- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Map Service:** Leaflet JS / OpenStreetMap (No API Key required)

## How to Execute (Installation Steps)

Follow these steps to run the project on your local machine:

### 1. Prerequisites
- Install **XAMPP** (or WAMP/MAMP) to run PHP and MySQL.

### 2. Setup Project Files
- Download or Clone this repository.
- Copy the project folder `food-waste-management-system` (or whatever you named it).
- Paste it inside your XAMPP installation directory: `C:\xampp\htdocs\`.

### 3. Database Configuration
- Open **XAMPP Control Panel** and start **Apache** and **MySQL**.
- Open your browser and go to `http://localhost/phpmyadmin`.
- Create a new database named **`demo`**.
- Click **Import** tab and choose the SQL file located in the `database/` folder (e.g., `demo.sql`).
- **Important:** If the `notifications` table is missing in the SQL file, run this query in the SQL tab:
  ```sql
  CREATE TABLE notifications (
      id INT(11) NOT NULL AUTO_INCREMENT,
      message TEXT NOT NULL,
      status VARCHAR(50) DEFAULT 'unread',
      date DATETIME DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (id)
  );