<?php
session_start(); // FIXED: Uncommented this line so login works!

// FIXED: Changed back to "localhost" because "3307" was causing your crashes earlier.
// If you are 100% sure you use port 3307, change it back.
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, 'demo');

$msg=0;
if(isset($_POST['sign']))
{
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $location = mysqli_real_escape_string($connection, $_POST['district']);

    $pass = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "select * from delivery_persons where email='$email'";
    $result = mysqli_query($connection, $sql);
    $num = mysqli_num_rows($result);
    
    if($num == 1){
        echo "<h1><center>Account already exists</center></h1>";
    }
    else{
        $query = "insert into delivery_persons(name,email,password,city) values('$username','$email','$pass','$location')";
        $query_run = mysqli_query($connection, $query);
        
        if($query_run)
        {
            // --- FIXED: UNCOMMENTED THIS SECTION ---
            // This tells the next page who you are and WHICH CITY to show
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $username;
            $_SESSION['location'] = $location; // Crucial for filtering orders!
            $_SESSION['is_login'] = true;
            // ---------------------------------------
            
            header("location:delivery.php");
        }
        else{
            echo '<script type="text/javascript">alert("Data not saved. Try again.")</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Registration</title>
    <link rel="stylesheet" href="deliverycss.css">
  </head>
  <body>
    <div class="center">
      <h1>Register Delivery</h1>
      <form method="post" action="">
        <div class="txt_field">
          <input type="text" name="username" required/>
          <span></span>
          <label>Username</label>
        </div>
        <div class="txt_field">
          <input type="password" name="password" required/>
          <span></span>
          <label>Password</label>
        </div>
        <div class="txt_field">
            <input type="email" name="email" required/>
            <span></span>
            <label>Email</label>
        </div>
        
        <div class="">
           <select id="district" name="district" style="padding:10px; padding-left: 20px; width: 100%; border: 1px solid #adadad; border-radius: 5px; margin-bottom: 20px;">
              <option value="chennai">Chennai</option>
              <option value="kancheepuram">Kancheepuram</option>
              <option value="thiruvallur">Thiruvallur</option>
              <option value="vellore">Vellore</option>
              <option value="tiruvannamalai">Tiruvannamalai</option>
              <option value="tiruvallur">Tiruvallur</option>
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
              <option value="madurai" selected>Madurai</option>
              <option value="virudhunagar">Virudhunagar</option>
              <option value="dindigul">Dindigul</option>
              <option value="ramanathapuram">Ramanathapuram</option>
              <option value="sivaganga">Sivaganga</option>
              <option value="thoothukkudi">Thoothukkudi</option>
              <option value="tirunelveli">Tirunelveli</option>
              <option value="tenkasi">Tenkasi</option>
              <option value="kanniyakumari">Kanniyakumari</option>
            </select> 
        </div>
        
        <input type="submit" name="sign" value="Register">
        <div class="signup_link">
          Already a member? <a href="deliverylogin.php">Sign in</a>
        </div>
      </form>
    </div>
  </body>
</html>