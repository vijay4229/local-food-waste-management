<?php
ob_start();
session_start();
include '../connection.php';

if($_SESSION['name']==''){
    header("location:deliverylogin.php");
}

$name = $_SESSION['name'];
$email = $_SESSION['email'];
$id = $_SESSION['Did'];

if(isset($_POST['mark_delivered'])) {
    $fid = $_POST['order_id'];
    $sql = "UPDATE food_donations SET delivery_status='Delivered' WHERE Fid='$fid'";
    $result = mysqli_query($connection, $sql);
    
    if($result){
        header("location:deliverymyord.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="delivery.css">
    <link rel="stylesheet" href="../home.css">
    <style>
        .table-container { padding: 20px; overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        .table th, .table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        .table th { background: #06C167; color: white; }
        .btn-delivered { background: #28a745; color: white; border: none; padding: 8px 15px; cursor: pointer; border-radius: 4px; }
        .status-done { color: green; font-weight: bold; border: 1px solid green; padding: 5px; border-radius: 5px; }
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
            <li><a href="delivery.php">Home</a></li>
            <li><a href="openmap.php">map</a></li>
            <li><a href="deliverymyord.php" class="active">myorders</a></li>
        </ul>
    </nav>
</header>
<script>
    hamburger=document.querySelector(".hamburger");
    hamburger.onclick =function(){
        navBar=document.querySelector(".nav-bar");
        navBar.classList.toggle("active");
    }
</script>

<div class="log">
    <a href="delivery.php" style="margin: 20px; display: inline-block;">Take orders</a>
    <p style="text-align: center; font-size: 28px;">Order assigned to you</p>
</div>

<div class="table-container">
    <div class="table-wrapper">
        <?php
        $sql = "SELECT fd.Fid AS Fid, fd.name, fd.phoneno, fd.date, fd.address as From_address, fd.delivery_status, ad.name AS delivery_person_name, ad.address AS To_address FROM food_donations fd LEFT JOIN admin ad ON fd.assigned_to = ad.Aid where delivery_by='$id'";
        $result=mysqli_query($connection, $sql);
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Date</th>
                    <th>Pickup Address</th>
                    <th>Delivery Address</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td data-label="name"><?php echo $row['name']; ?></td>
                    <td data-label="phoneno"><?php echo $row['phoneno']; ?></td>
                    <td data-label="date"><?php echo $row['date']; ?></td>
                    <td data-label="Pickup Address"><?php echo $row['From_address']; ?></td>
                    <td data-label="Delivery Address"><?php echo $row['To_address']; ?></td>
                    <td data-label="Status">
                        <?php 
                        if($row['delivery_status'] == 'Delivered') {
                            echo '<span style="color:green;">Delivered</span>';
                        } else {
                            echo '<span style="color:orange;">On Way</span>';
                        }
                        ?>
                    </td>
                    <td data-label="Action">
                        <?php if($row['delivery_status'] != 'Delivered') { ?>
                            <form method="post">
                                <input type="hidden" name="order_id" value="<?php echo $row['Fid']; ?>">
                                <button type="submit" name="mark_delivered" class="btn-delivered">Mark Delivered</button>
                            </form>
                        <?php } else { ?>
                            <span class="status-done">Done</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>