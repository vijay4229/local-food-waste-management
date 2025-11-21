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

### Enable Delivery Status Tracking
SQL:
    ALTER TABLE food_donations 
    ADD delivery_status VARCHAR(50) DEFAULT 'Pending';

### Database Connection (PHP)
PHP:
    <?php
    $connection = mysqli_connect("localhost", "root", "");
    ?>

---

## ▶️ Run Application
Visit:  
`http://localhost/food-waste-management-system/`

---

## 🧪 Test Workflow
- User: Register → Donate Food  
- Admin: Check Notifications → View Donations  
- Delivery Boy: Take Order → Mark Delivered  
- Admin: Refresh → Status becomes Delivered  

---

## 📜 License
This project is **open-source** for educational use.
