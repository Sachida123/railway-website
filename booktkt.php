<?php 
session_start();
if(empty($_SESSION['user_info'])){
    echo "<script type='text/javascript'>alert('Please login before proceeding further!');</script>";
}
$conn = mysqli_connect("localhost","root","","railway");
if(!$conn){  
    echo "<script type='text/javascript'>alert('Database failed');</script>";
    die('Could not connect: '.mysqli_connect_error());  
}

// Create the trains table if it doesn't exist
$create_table_query = "CREATE TABLE IF NOT EXISTS trains (
                        t_no INT AUTO_INCREMENT PRIMARY KEY,
                        t_name VARCHAR(255) NOT NULL
                    )";
mysqli_query($conn, $create_table_query);

if (isset($_POST['submit']))
{
    $trains = $_POST['trains'];
    $email = $_SESSION['user_info'];

    // Query to select t_no based on t_name from trains table
    $sql = "SELECT t_no FROM trains WHERE t_name = '$trains'";
    $result = mysqli_query($conn, $sql);

    // Check if a row is returned
    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $t_no = $row['t_no'];

        // Update passengers table with t_no
        $query = "UPDATE passengers SET t_no='$t_no' WHERE email='$email';";
        if(mysqli_query($conn, $query)) {  
            $message = "Ticket booked successfully";
        } else {
            $message = "Transaction failed";
        }
    } else {
        $message = "Train not found";
    }
    echo "<script type='text/javascript'>alert('$message');</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book a ticket</title>
    <link rel="stylesheet" href="STYLE.CSS">
    <style type="text/css">
        #booktkt {
            margin:auto;
            margin-top: 50px;
            width: 40%;
            height: 60%;
            padding: auto;
            padding-top: 50px;
            padding-left: 50px;
            background-color: rgba(0,0,0,0.3);
            border-radius: 25px;
        }
        html { 
          background: url(img/bg7.jpg) no-repeat center center fixed; 
          -webkit-background-size: cover;
          -moz-background-size: cover;
          -o-background-size: cover;
          background-size: cover;
        }
        #journeytext {
            color: white;
            font-size: 28px;
            font-family: "Comic Sans MS", cursive, sans-serif;
        }
        #trains {
            margin-left: 90px;
            font-size: 15px;
        }
        #submit {
            margin-left: 150px;
            margin-bottom: 40px;
            margin-top: 30px
        }
    </style>
    <script type="text/javascript">
        function validate() {
            var trains = document.getElementById("trains");
            if (trains.selectedIndex == 0) {
                alert("Please select your train");
                trains.focus();
                return false;        
            }
        }
    </script>
</head>
<body>
    <?php
        include ('header.php');
    ?>
    <div id="booktkt">
    <h1 align="center" id="journeytext">Choose your journey</h1><br/><br/>
    <form method="post" name="journeyform" onsubmit="return validate()">
        <select id="trains" name="trains" required>
            <option selected disabled>-------------------Select trains here----------------------</option>
            <option value="rajdhani" >Rajdhani Express - Mumbai Central to Delhi</option>
            <option value="duronto" >Duronto Express - Mumbai Central to Ernakulum</option>
            <option value="geetanjali">Geetanjali Express - CST to Kolkata</option>
            <option value="garibrath" >Garib Rath - Udaipur to Jammu Tawi</option>
            <option value="mysoreexp" >Mysore Express - Talguppa to Mysore Jn</option>
        </select>
        <br/><br/>
        <input type="submit" name="submit" id="submit" class="button" />
    </form>
    </div>
</body>
</html>
