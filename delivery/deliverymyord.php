<?php
ob_start(); 
session_start(); // Crucial: Starts or resumes the session

// Include database connection files
include '../connection.php';
include("connect.php"); 

// Enhanced Session/Login Check
// Checks if EITHER 'name' OR 'Did' is missing from the session.
if(empty($_SESSION['name']) || empty($_SESSION['Did'])){
    header("location:deliverylogin.php");
    exit(); // Always use exit() after a header redirect to stop script execution
}

// Session variables are now safely assigned
$name = $_SESSION['name'];
$id = $_SESSION['Did'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery My Orders</title>
    <link rel="stylesheet" href="delivery.css">
    <link rel="stylesheet" href="../home.css">
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
                <li><a href="delivery.php" >Home</a></li>
                <li><a href="openmap.php" >map</a></li>
                <li><a href="deliverymyord.php"  class="active">myorders</a></li>
    
            </ul>
        </nav>
    </header>
    <br>
    <script>
        hamburger=document.querySelector(".hamburger");
        hamburger.onclick =function(){
            navBar=document.querySelector(".nav-bar");
            navBar.classList.toggle("active");
        }
    </script>
    <style>
        .itm{
            background-color: white;
            display: grid;
        }
        .itm img{
            width: 400px;
            height: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        p{
            text-align: center; font-size: 28PX;color: black; 
        }
        a{
            /* text-decoration: underline; */
        }
        @media (max-width: 767px) {
            .itm{
                /* float: left; */
                
            }
            .itm img{
                width: 350px;
                height: 350px;
            }
        }
    </style>

        <div class="itm" >

            <img src="../img/delivery.gif" alt="" width="400" height="400"> 
          
        </div>

        <div class="get">
            <?php

// Define the SQL query to fetch orders assigned to the current delivery person ($id)
// NOTE: $id is directly used in the query without sanitization. In a production environment, 
// this is a severe security risk (SQL Injection) and should be fixed using prepared statements.
$sql = "SELECT fd.Fid AS Fid, fd.name,fd.phoneno,fd.date,fd.delivery_by, fd.address as From_address, 
ad.name AS delivery_person_name, ad.address AS To_address
FROM food_donations fd
LEFT JOIN admin ad ON fd.assigned_to = ad.Aid where delivery_by='$id';
";

// Execute the query
$result=mysqli_query($connection, $sql);

// Check for errors
if (!$result) {
    // Using $connection instead of $conn for mysqli_error
    die("Error executing query: " . mysqli_error($connection)); 
}

// Fetch the data as an associative array
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Handle order assignment (This logic seems to belong more to 'delivery.php' if it's about taking orders, 
// but is kept here as per your original code)
if (isset($_POST['food']) && isset($_POST['delivery_person_id'])) {
    $order_id = $_POST['order_id'];
    $delivery_person_id = $_POST['delivery_person_id'];

    // NOTE: SQL Injection risk here as well. Use prepared statements in production.
    $sql = "UPDATE food_donations SET delivery_by = $delivery_person_id WHERE Fid = $order_id";
    $result=mysqli_query($connection, $sql);

    if (!$result) {
        // Using $connection instead of $conn for mysqli_error
        die("Error assigning order: " . mysqli_error($connection));
    }

    // Reload the page to prevent duplicate assignments
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit; // Use exit() after header
    // ob_end_flush(); // No need for ob_end_flush() here after exit()
}
// mysqli_close($conn); // If you close the connection here, the rest of the page will fail

?>
<div class="log">
<a href="delivery.php">Take orders</a>
<p>Order assigned to you</p>
<br>
</div>
  

<div class="table-container">
                  <div class="table-wrapper">
        <table class="table">
        <thead>
        <tr>
            <th >Name</th>
                                    <th>phoneno</th>
            <th>date/time</th>
            <th>Pickup address</th>
            <th>Delivery address</th>
                     
          
           
        </tr>
        </thead>
       <tbody>

        <?php foreach ($data as $row) { ?>
        <?php    
            echo "<tr>";
            echo "<td data-label=\"name\">".$row['name']."</td>";
            echo "<td data-label=\"phoneno\">".$row['phoneno']."</td>";
            echo "<td data-label=\"date\">".$row['date']."</td>";
            echo "<td data-label=\"Pickup Address\">".$row['From_address']."</td>";
            echo "<td data-label=\"Delivery Address\">".$row['To_address']."</td>";
            echo "<td>"; // Start of the extra data label cell
        ?>
        
                                                    </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

            </div>

        
     
        

   <br>
   <br>
</body>
</html>

<?php
// End output buffering after all content is sent
ob_end_flush(); 
?>