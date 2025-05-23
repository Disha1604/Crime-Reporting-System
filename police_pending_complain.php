<?php
session_start();
if (!isset($_SESSION['x'])) {
    header("location:policelogin.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "crime_portal");
if (!$conn) {
    die("Could not connect: " . mysqli_connect_error());
}

$p_id = $_SESSION['pol']; // Get police ID from session

if (isset($_POST['s2'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $cid = $_POST['cid'];
        $_SESSION['cid'] = $cid;

        if (empty($p_id)) {
            die("Error: Police ID session not set.");
        }

        $query = "SELECT * FROM complaint WHERE c_id='$cid' AND p_id='$p_id'";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Error in SQL Query: " . mysqli_error($conn));
        }

        $row = mysqli_fetch_assoc($result);

        if ($row) {
            header("location:police_complainDetails.php");
            exit();
        } else {
            echo "<script>alert('Complaint ID not found or not assigned to you');</script>";
        }
    }
}

// Fetch all pending complaints assigned to this officer
$result = mysqli_query($conn, "SELECT c_id, type_crime, d_o_c, location FROM complaint WHERE p_id='$p_id' AND pol_status='In Process' ORDER BY c_id DESC");

if (!$result) {
    die("Error in fetching complaints: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Police Pending Complaints</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
    <script>
        function f1() {
            var sta2 = document.getElementById("ciid").value;
            var x2 = sta2.indexOf(' ');
            if (sta2 !== "" && x2 >= 0) {
                document.getElementById("ciid").value = "";
                alert("Blank Field Found");
            }
        }
    </script>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="home.php"><b>Crime Portal</b></a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="official_login.php">Official Login</a></li>
                <li><a href="policelogin.php">Police Login</a></li>
                <li class="active"><a href="police_pending_complain.php">Police Home</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="police_pending_complain.php">Pending Complaints</a></li>
                <li><a href="police_complete.php">Completed Complaints</a></li>
                <li><a href="p_logout.php">Logout &nbsp;<i class="fa fa-sign-out" aria-hidden="true"></i></a></li>
            </ul>
        </div>
    </div>
</nav>

<form style="margin-top: 7%; margin-left: 40%;" method="post">
    <input type="text" name="cid" style="width: 250px; height: 30px;" placeholder="&nbsp Complaint Id" onfocusout="f1()" required id="ciid">
    <div>
        <input class="btn btn-primary" type="submit" value="Search" name="s2" style="margin-top: 10px;">
    </div>
</form>

<div style="padding:50px;">
    <table class="table table-bordered">
        <thead style="background-color: black; color: white;">
        <tr>
            <th>Complaint Id</th>
            <th>Type of Crime</th>
            <th>Date of Crime</th>
            <th>Location of Crime</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($rows = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $rows['c_id']; ?></td>
                <td><?php echo $rows['type_crime']; ?></td>
                <td><?php echo $rows['d_o_c']; ?></td>
                <td><?php echo $rows['location']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-2.1.4.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>
