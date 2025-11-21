<?php
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
    <title>Donations List</title> 
    
    <?php
    $connection=mysqli_connect("localhost","root","");
    $db=mysqli_select_db($connection,'demo');
    ?>

    <style>
        .notification-box { position: relative; cursor: pointer; margin-right: 20px; display: flex; align-items: center; }
        .notification-badge { position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 10px; font-weight: bold; display: none; }
        .notification-dropdown { display: none; position: absolute; right: 0; top: 40px; background: white; width: 300px; box-shadow: 0px 5px 15px rgba(0,0,0,0.2); border-radius: 5px; z-index: 1000; overflow: hidden; border: 1px solid #ddd; }
        .notif-header { background: #06C167; color: white; padding: 10px; font-weight: bold; text-align: center; }
        .notif-item { padding: 12px; border-bottom: 1px solid #eee; font-size: 13px; color: #333; background: white; text-align: left; }
        .notif-item:hover { background: #f9f9f9; }
        .notif-item.unread { background: #e8f8f5; font-weight: bold; }
        .notif-time { display: block; font-size: 11px; color: #888; margin-top: 4px; }
    </style>
</head>
<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image"></div>
            <span class="logo_name">ADMIN</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="admin.php"><i class="uil uil-estate"></i><span class="link-name">Dahsboard</span></a></li>
                <li><a href="analytics.php"><i class="uil uil-chart"></i><span class="link-name">Analytics</span></a></li>
                <li><a href="donate.php"><i class="uil uil-heart"></i><span class="link-name">Donates</span></a></li>
                <li><a href="feedback.php"><i class="uil uil-comments"></i><span class="link-name">Feedbacks</span></a></li>
                <li><a href="adminprofile.php"><i class="uil uil-user"></i><span class="link-name">Profile</span></a></li>
            </ul>
            
            <ul class="logout-mode">
                <li><a href="../logout.php"><i class="uil uil-signout"></i><span class="link-name">Logout</span></a></li>
                <li class="mode"><a href="#"><i class="uil uil-moon"></i><span class="link-name">Dark Mode</span></a><div class="mode-toggle"><span class="switch"></span></div></li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
            <p class="logo">Food <b style="color: #06C167;">Donate</b></p>
            <p class="user"></p>
            
            <div class="notification-box" onclick="toggleNotif()">
                <i class="uil uil-bell" style="font-size: 24px; color: #333;"></i>
                <span class="notification-badge" id="notif-count">0</span>
                <div class="notification-dropdown" id="notif-dropdown">
                    <div class="notif-header">Notifications</div>
                    <div id="notif-list"><div class="notif-item">Loading...</div></div>
                </div>
            </div>
        </div>
        <br><br><br>
    
        <div class="activity">
            <div class="location">
                <form method="post">
                    <label for="location" class="logo">Select Location:</label>
                    <select id="location" name="location">
                        <option value="chennai">Chennai</option>
                        <option value="kancheepuram">Kancheepuram</option>
                        <option value="thiruvallur">Thiruvallur</option>
                        <option value="vellore">Vellore</option>
                        <option value="tiruvannamalai">Tiruvannamalai</option>
                        <option value="tiruppur">Tiruppur</option>
                        <option value="coimbatore">Coimbatore</option>
                        <option value="erode">Erode</option>
                        <option value="salem">Salem</option>
                        <option value="namakkal">Namakkal</option>
                        <option value="tiruchirappalli">Tiruchirappalli</option>
                        <option value="thanjavur">Thanjavur</option>
                        <option value="pudukkottai">Pudukkottai</option>
                        <option value="karur">Karur</option>
                        <option value="ariyalur">Ariyalur</option>
                        <option value="perambalur">Perambalur</option>
                        <option value="madurai">Madurai</option>
                        <option value="virudhunagar">Virudhunagar</option>
                        <option value="dindigul">Dindigul</option>
                        <option value="ramanathapuram">Ramanathapuram</option>
                        <option value="sivaganga">Sivaganga</option>
                        <option value="thoothukkudi">Thoothukkudi</option>
                        <option value="tirunelveli">Tirunelveli</option>
                        <option value="tenkasi">Tenkasi</option>
                        <option value="kanniyakumari">Kanniyakumari</option>
                    </select>
                    <input type="submit" value="Get Details">
                </form>
                <br>

                <?php
                if(isset($_POST['location'])) {
                    $location = $_POST['location'];
                    $sql = "SELECT * FROM food_donations WHERE location='$location'";
                    $result=mysqli_query($connection, $sql);
                    
                    if (mysqli_num_rows($result) > 0) {
                        // I have added the 'Status' header here
                        echo "<div class=\"table-container\">
                                <div class=\"table-wrapper\">
                                    <table class=\"table\">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Food</th>
                                                <th>Category</th>
                                                <th>Phone No</th>
                                                <th>Date/Time</th>
                                                <th>Address</th>
                                                <th>Quantity</th>
                                                <th>Status</th> </tr>
                                        </thead>
                                        <tbody>";

                        while($row = mysqli_fetch_assoc($result)) {
                            // Calculate Status Color
                            $status = '<span style="color:red;">Pending</span>';
                            if($row['delivery_status'] == 'Delivered'){
                                $status = '<span style="color:green; font-weight:bold;">Delivered</span>';
                            } elseif($row['assigned_to'] != null){
                                $status = '<span style="color:orange;">Assigned</span>';
                            }

                            // I have added the status column data here
                            echo "<tr>
                                    <td data-label=\"name\">".$row['name']."</td>
                                    <td data-label=\"food\">".$row['food']."</td>
                                    <td data-label=\"category\">".$row['category']."</td>
                                    <td data-label=\"phoneno\">".$row['phoneno']."</td>
                                    <td data-label=\"date\">".$row['date']."</td>
                                    <td data-label=\"Address\">".$row['address']."</td>
                                    <td data-label=\"quantity\">".$row['quantity']."</td>
                                    <td data-label=\"Status\">".$status."</td>
                                  </tr>";
                        }
                        echo "</tbody></table></div></div>";
                    } else {
                        echo "<p>No results found in $location.</p>";
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <script src="admin.js"></script>
    <script>
    function toggleNotif() {
        const dropdown = document.getElementById('notif-dropdown');
        dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
    }

    function loadNotifications() {
        fetch('fetch_notifications.php?t=' + new Date().getTime())
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                const badge = document.getElementById('notif-count');
                if(data.count > 0){
                    badge.style.display = 'block';
                    badge.innerText = data.count;
                } else {
                    badge.style.display = 'none';
                }

                const list = document.getElementById('notif-list');
                list.innerHTML = '';

                if(data.messages.length === 0){
                    list.innerHTML = '<div class="notif-item">No new notifications</div>';
                } else {
                    data.messages.forEach(msg => {
                        const itemClass = msg.status === 'unread' ? 'notif-item unread' : 'notif-item';
                        list.innerHTML += `<div class="${itemClass}"><strong>${msg.message}</strong><span class="notif-time">${msg.date}</span></div>`;
                    });
                }
            } catch (e) { console.error("JSON Parse Error", e); }
        })
        .catch(error => console.error('Fetch Error:', error));
    }
    setInterval(loadNotifications, 5000);
    loadNotifications();
    </script>
</body>
</html>