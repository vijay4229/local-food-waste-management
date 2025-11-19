<?php
ob_start(); 
include("connect.php"); 
if($_SESSION['name']==''){
    header("location:signin.php");
}
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link rel="stylesheet" href="admin.css">
     
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <title>Admin Dashboard Panel</title> 
    
<?php
 // FIXED: Changed "localhost:3307" to "localhost"
 $connection=mysqli_connect("localhost","root","");
 $db=mysqli_select_db($connection,'demo');
?>
<style>
    /* NOTIFICATION STYLES */
    .notification-box {
        position: relative;
        cursor: pointer;
        margin-right: 20px;
        display: flex;
        align-items: center;
    }
    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: red;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 10px;
        font-weight: bold;
        display: none;
    }
    .notification-dropdown {
        display: none;
        position: absolute;
        right: 0;
        top: 40px;
        background: white;
        width: 300px;
        box-shadow: 0px 5px 15px rgba(0,0,0,0.2);
        border-radius: 5px;
        z-index: 1000;
        overflow: hidden;
        border: 1px solid #ddd;
    }
    .notif-header {
        background: #06C167;
        color: white;
        padding: 10px;
        font-weight: bold;
        text-align: center;
    }
    .notif-item {
        padding: 12px;
        border-bottom: 1px solid #eee;
        font-size: 13px;
        color: #333;
        background: white;
        text-align: left;
    }
    .notif-item:hover { background: #f9f9f9; }
    .notif-item.unread { background: #e8f8f5; font-weight: bold; }
    .notif-time { display: block; font-size: 11px; color: #888; margin-top: 4px; }
</style>
</head>
<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image">
                </div>

            <span class="logo_name">ADMIN</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="#">
                    <i class="uil uil-estate"></i>
                    <span class="link-name">Dahsboard</span>
                </a></li>
                <li><a href="analytics.php">
                    <i class="uil uil-chart"></i>
                    <span class="link-name">Analytics</span>
                </a></li>
                <li><a href="donate.php">
                    <i class="uil uil-heart"></i>
                    <span class="link-name">Donates</span>
                </a></li>
                <li><a href="feedback.php">
                    <i class="uil uil-comments"></i>
                    <span class="link-name">Feedbacks</span>
                </a></li>
                <li><a href="adminprofile.php">
                    <i class="uil uil-user"></i>
                    <span class="link-name">Profile</span>
                </a></li>
                </ul>
            
            <ul class="logout-mode">
                <li><a href="../logout.php">
                    <i class="uil uil-signout"></i>
                    <span class="link-name">Logout</span>
                </a></li>

                <li class="mode">
                    <a href="#">
                        <i class="uil uil-moon"></i>
                    <span class="link-name">Dark Mode</span>
                </a>

                <div class="mode-toggle">
                  <span class="switch"></span>
                </div>
            </li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
            <p  class ="logo" >Food <b style="color: #06C167; ">Donate</b></p>
             <p class="user"></p>
            
            <div class="notification-box" onclick="toggleNotif()">
                <i class="uil uil-bell" style="font-size: 24px; color: #333;"></i>
                <span class="notification-badge" id="notif-count">0</span>
                
                <div class="notification-dropdown" id="notif-dropdown">
                    <div class="notif-header">Notifications</div>
                    <div id="notif-list">
                        <div class="notif-item">Loading...</div>
                    </div>
                </div>
            </div>
            </div>

        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class="uil uil-tachometer-fast-alt"></i>
                    <span class="text">Dashboard</span>
                </div>

                <div class="boxes">
                    <div class="box box1">
                        <i class="uil uil-user"></i>
                        <span class="text">Total users</span>
                        <?php
                           $query="SELECT count(*) as count FROM  login";
                           $result=mysqli_query($connection, $query);
                           $row=mysqli_fetch_assoc($result);
                         echo "<span class=\"number\">".$row['count']."</span>";
                        ?>
                        </div>
                    <div class="box box2">
                        <i class="uil uil-comments"></i>
                        <span class="text">Feedbacks</span>
                        <?php
                           $query="SELECT count(*) as count FROM  user_feedback";
                           $result=mysqli_query($connection, $query);
                           $row=mysqli_fetch_assoc($result);
                         echo "<span class=\"number\">".$row['count']."</span>";
                        ?>
                        </div>
                    <div class="box box3">
                        <i class="uil uil-heart"></i>
                        <span class="text">Total doantes</span>
                        <?php
                           $query="SELECT count(*) as count FROM food_donations";
                           $result=mysqli_query($connection, $query);
                           $row=mysqli_fetch_assoc($result);
                         echo "<span class=\"number\">".$row['count']."</span>";
                        ?>
                        </div>
                </div>
            </div>

            <div class="activity">
                <div class="title">
                    <i class="uil uil-clock-three"></i>
                    <span class="text">Recent Donations</span>
                </div>
            <div class="get">
            <?php

$loc= $_SESSION['location'];

// --- FIXED: REMOVED CITY FILTER SO ADMIN SEES ALL CITIES ---
// Old code: $sql = "SELECT * FROM food_donations WHERE assigned_to IS NULL and location=\"$loc\"";
$sql = "SELECT * FROM food_donations WHERE assigned_to IS NULL";
// -----------------------------------------------------------

// Execute the query
$result=mysqli_query($connection, $sql);
$id=$_SESSION['Aid'];

// Check for errors
if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}

// Fetch the data as an associative array
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// If the delivery person has taken an order, update the assigned_to field in the database
if (isset($_POST['food']) && isset($_POST['delivery_person_id'])) {

    
    $order_id = $_POST['order_id'];
    $delivery_person_id = $_POST['delivery_person_id'];
    $sql = "SELECT * FROM food_donations WHERE Fid = $order_id AND assigned_to IS NOT NULL";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Order has already been assigned to someone else
        die("Sorry, this order has already been assigned to someone else.");
    }



    $sql = "UPDATE food_donations SET assigned_to = $delivery_person_id WHERE Fid = $order_id";
    // $result = mysqli_query($conn, $sql);
    $result=mysqli_query($connection, $sql);


    if (!$result) {
        die("Error assigning order: " . mysqli_error($conn));
    }

    // Reload the page to prevent duplicate assignments
    header('Location: ' . $_SERVER['REQUEST_URI']);
    // exit;
    ob_end_flush();
}
// mysqli_close($conn);


?>

<div class="table-container">
         <div class="table-wrapper">
        <table class="table">
        <thead>
        <tr>
            <th >Name</th>
            <th>food</th>
            <th>Category</th>
            <th>phoneno</th>
            <th>date/time</th>
            <th>address</th>
            <th>Quantity</th>
            </tr>
        </thead>
       <tbody>

        <?php foreach ($data as $row) { ?>
        <?php    echo "<tr><td data-label=\"name\">".$row['name']."</td><td data-label=\"food\">".$row['food']."</td><td data-label=\"category\">".$row['category']."</td><td data-label=\"phoneno\">".$row['phoneno']."</td><td data-label=\"date\">".$row['date']."</td><td data-label=\"Address\">".$row['address']."</td><td data-label=\"quantity\">".$row['quantity']."</td>";
?>
        
            <td data-label="Action" style="margin:auto">
                <?php if ($row['assigned_to'] == null) { ?>
                    <form method="post" action=" ">
                        <input type="hidden" name="order_id" value="<?= $row['Fid'] ?>">
                        <input type="hidden" name="delivery_person_id" value="<?= $id ?>">
                        <button type="submit" name="food">Get Food</button>
                    </form>
                <?php } else if ($row['assigned_to'] == $id) { ?>
                    Order assigned to you
                <?php } else { ?>
                    Order assigned to another delivery person
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

            </div>
            
        </div>
    </section>

    <script src="admin.js"></script>

   <script>
    // Toggle Dropdown
    function toggleNotif() {
        const dropdown = document.getElementById('notif-dropdown');
        dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
    }

    // Fetch Notifications via AJAX
    function loadNotifications() {
        // We add '?t=' + new Date().getTime() to force a fresh request (prevents caching)
        fetch('fetch_notifications.php?t=' + new Date().getTime())
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.text(); // Get text first to debug JSON issues
        })
        .then(text => {
            try {
                const data = JSON.parse(text); // Try to parse JSON
                
                // 1. Update Badge
                const badge = document.getElementById('notif-count');
                if(data.count > 0){
                    badge.style.display = 'block';
                    badge.innerText = data.count;
                } else {
                    badge.style.display = 'none';
                }

                // 2. Update List
                const list = document.getElementById('notif-list');
                list.innerHTML = ''; // Clear current list

                if(data.messages.length === 0){
                    list.innerHTML = '<div class="notif-item">No new notifications</div>';
                } else {
                    data.messages.forEach(msg => {
                        // Determine background color based on read/unread status
                        const itemClass = msg.status === 'unread' ? 'notif-item unread' : 'notif-item';
                        list.innerHTML += `
                            <div class="${itemClass}">
                                <strong>${msg.message}</strong>
                                <span class="notif-time">${msg.date}</span>
                            </div>`;
                    });
                }
            } catch (e) {
                console.error("JSON Parse Error:", e);
                console.log("Server Response:", text);
                document.getElementById('notif-list').innerHTML = 
                    '<div class="notif-item" style="color:red;">JSON Error. Check Console (F12).<br>Response was: ' + text.substring(0, 50) + '...</div>';
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            document.getElementById('notif-list').innerHTML = 
                '<div class="notif-item" style="color:red;">Connection Error:<br>' + error.message + '</div>';
        });
    }

    // Refresh every 5 seconds
    setInterval(loadNotifications, 5000);
    // Run on page load
    loadNotifications();
    </script>

</body>
</html>