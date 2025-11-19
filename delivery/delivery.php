<?php
ob_start();
session_start();
include '../connection.php'; // Standard connection file

// 1. Security Check
if(!isset($_SESSION['name']) || $_SESSION['name']==''){
    header("location:deliverylogin.php");
    exit;
}

// 2. Get User Details from Session
$name = $_SESSION['name'];
$email = $_SESSION['email'];

// 3. Fetch ID and City from Database (Most Accurate)
$sql_user = "SELECT Did, city FROM delivery_persons WHERE email='$email'";
$result_user = mysqli_query($connection, $sql_user);
$row_user = mysqli_fetch_assoc($result_user);

$id = $row_user['Did'];    // Delivery Person ID
$city = $row_user['city']; // Registered City

// 4. Handle "Take Order" Action
if(isset($_POST['take_order'])) {
    $order_id = $_POST['order_id'];
    $delivery_id = $_POST['delivery_person_id'];

    // Double check if order is still free
    $check_sql = "SELECT * FROM food_donations WHERE Fid='$order_id' AND delivery_by IS NOT NULL";
    $check_res = mysqli_query($connection, $check_sql);

    if(mysqli_num_rows($check_res) > 0){
        echo "<script>alert('Sorry, someone else just took this order!'); window.location.href='delivery.php';</script>";
    } else {
        // Assign order
        $update_sql = "UPDATE food_donations SET delivery_by='$delivery_id' WHERE Fid='$order_id'";
        $update_res = mysqli_query($connection, $update_sql);
        
        if($update_res){
            echo "<script>alert('Order successfully assigned to you!'); window.location.href='deliverymyord.php';</script>";
            exit;
        } else {
            echo "<script>alert('Database Error. Try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Dashboard</title>
    <link rel="stylesheet" href="../home.css">
    <link rel="stylesheet" href="delivery.css">
    <style>
        /* Quick Fix CSS */
        .table-container { padding: 20px; overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; background: white; }
        .table th, .table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        .table th { background: #06C167; color: white; }
        .btn-take { background: #06C167; color: white; border: none; padding: 8px 15px; cursor: pointer; border-radius: 4px; }
        .btn-take:hover { background: #048a4b; }
        .welcome-msg { text-align: center; margin: 20px 0; font-size: 24px; color: #333; }
        .city-badge { background: #eee; padding: 5px 10px; border-radius: 15px; font-size: 16px; color: #555; }
    </style>
</head>
<body>

<header>
    <div class="logo">Food <b style="color: #06C167;">Donate</b></div>
    <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <nav class="nav-bar">
        <ul>
            <li><a href="#home" class="active">Home</a></li>
            <li><a href="openmap.php">Map</a></li>
            <li><a href="deliverymyord.php">My Orders</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<script>
    hamburger = document.querySelector(".hamburger");
    hamburger.onclick = function() {
        navBar = document.querySelector(".nav-bar");
        navBar.classList.toggle("active");
    }
</script>

<div class="welcome-msg">
    Welcome, <b><?php echo $name; ?></b><br>
    <span class="city-badge">Location: <?php echo ucfirst($city); ?></span>
</div>

<div class="itm" style="text-align:center;">
    <img src="../img/delivery.gif" alt="Delivery Animation" width="400" height="400" style="max-width:100%; height:auto;">
</div>

<div class="get">
    <?php
    // 5. Fetch Orders Logic
    // Logic: Show orders where Admin has assigned it (assigned_to is set) 
    // BUT no delivery boy has picked it up yet (delivery_by is NULL)
    // AND matches the delivery boy's city
    
    $sql = "SELECT fd.Fid AS Fid, fd.location, fd.name, fd.phoneno, fd.date, fd.address as From_address, 
            ad.name AS admin_name, ad.address AS To_address
            FROM food_donations fd
            LEFT JOIN admin ad ON fd.assigned_to = ad.Aid 
            WHERE fd.assigned_to IS NOT NULL 
            AND fd.delivery_by IS NULL 
            AND fd.location='$city'";

    $result = mysqli_query($connection, $sql);
    
    // Check if we have orders
    if(mysqli_num_rows($result) > 0) {
    ?>
    
    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Donor Name</th>
                        <th>Phone</th>
                        <th>Date</th>
                        <th>Pickup Address</th>
                        <th>Delivery Address (Admin)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)) { 
                        // Fallback if admin address is null (sometimes happens in testing)
                        $to_address = $row['To_address'] ? $row['To_address'] : "NGO/Admin Office"; 
                    ?>
                    <tr>
                        <td data-label="Name"><?php echo $row['name']; ?></td>
                        <td data-label="Phone"><?php echo $row['phoneno']; ?></td>
                        <td data-label="Date"><?php echo $row['date']; ?></td>
                        <td data-label="Pickup"><?php echo $row['From_address']; ?></td>
                        <td data-label="Delivery"><?php echo $to_address; ?></td>
                        <td data-label="Action">
                            <form method="post" action="">
                                <input type="hidden" name="order_id" value="<?php echo $row['Fid']; ?>">
                                <input type="hidden" name="delivery_person_id" value="<?php echo $id; ?>">
                                <button type="submit" name="take_order" class="btn-take">Take Order</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php 
    } else {
        echo "<h2 style='text-align:center; color: gray; margin-top: 20px;'>No pending orders in $city right now.</h2>";
    } 
    ?>
</div>

</body>
</html>