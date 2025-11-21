# Food Waste Management System

A web-based application developed using PHP and MySQL that connects food donors with charities and NGOs to reduce food wastage. The system facilitates the donation process through three distinct modules: Admin, User (Donor), and Delivery Person.

## 🚀 Features

### User (Donor) Module
- **Registration & Login:** Secure account creation.
- **Donate Food:** Post details about leftover food (Veg/Non-veg, Raw/Cooked, Quantity).
- **History:** View past donation records.

### Admin Module
- **Dashboard Analytics:** View total users, donations, and feedback stats.
- **🔔 Notification Center:** Real-time alerts (Bell Icon) when a new donation is made.
- **Donation Management:** View all donations and filter them by City/Location.
- **Status Tracking:** Monitor live status of donations (Pending → Assigned → Delivered).
- **User Management:** View feedback and profiles.

### Delivery Module
- **City-Based Filtering:** Delivery agents only see orders available in their registered city.
- **Take Order:** Accept unassigned orders from the dashboard.
- **✅ Status Update:** Mark orders as "Delivered" once the food reaches the destination.
- **Map Integration:** View current location using Leaflet Maps (OpenStreetMap).

## 🛠️ Tech Stack
- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Map Service:** Leaflet JS / OpenStreetMap (No API Key required)

## ⚙️ How to Execute (Installation Steps)

Follow these steps to run the project on your local machine:

### 1. Prerequisites
- Install **XAMPP** (or WAMP/MAMP) to run PHP and MySQL.

### 2. Setup Project Files
- Download or Clone this repository.
- Copy the project folder `food-waste-management-system`.
- Paste it inside your XAMPP installation directory: `C:\xampp\htdocs\`.

### 3. Database Configuration
- Open **XAMPP Control Panel** and start **Apache** and **MySQL**.
- Open your browser and go to `http://localhost/phpmyadmin`.
- Create a new database named **`demo`**.
- Click **Import** tab and choose the SQL file located in the `database/` folder (e.g., `demo.sql`).

### 4. ⚠️ Crucial Database Updates
**You must run these SQL commands** in PHPMyAdmin to enable the Notification and Delivery Status features:

1. **Click the "SQL" tab** in PHPMyAdmin and run this to create the notification system:
   ```sql
   CREATE TABLE notifications (
       id INT(11) NOT NULL AUTO_INCREMENT,
       message TEXT NOT NULL,
       status VARCHAR(50) DEFAULT 'unread',
       date DATETIME DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (id)
   );
````

2.  **Run this command** to enable delivery status tracking:
    ALTER TABLE food_donations ADD delivery_status VARCHAR(50) DEFAULT 'Pending';
    ```

### 5\. Configure Connection

  - Open `connection.php` and `admin/connect.php`.
  - Ensure the credentials match your XAMPP settings (Default user: `root`, password: empty).
    ```php
    $connection = mysqli_connect("localhost", "root", "");
    ```

### 6\. Run the Application

  - Open your browser.
  - Type: `http://localhost/food-waste-management-system/`

## 🧪 How to Test the Flow

1.  **User:** Register and Donate Food.
2.  **Admin:** Log in -\> Click the **Bell Icon** to see the alert -\> Go to "Donates".
3.  **Delivery Boy:** Log in -\> Click "Take Order" -\> Go to "My Orders" -\> Click "Mark Delivered".
4.  **Admin:** Refresh "Donates" page to see status change to **"Delivered"**.
