<!DOCTYPE html>
<html>
<head>
<?php
if(isset($_POST['s'])) {
    session_start();
    $_SESSION['x'] = 1;

    // Establish database connection
    $conn = mysqli_connect("localhost", "root", "", "crime_portal");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Ensure form fields are set
    if(isset($_POST['email']) && isset($_POST['password'])) {
        $name = mysqli_real_escape_string($conn, $_POST['email']);
        $pass = mysqli_real_escape_string($conn, $_POST['password']);
        
        $_SESSION['u_id'] = $name;

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT u_id, u_pass FROM user WHERE u_id = ? AND u_pass = ?");
        $stmt->bind_param("ss", $name, $pass);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            echo "<script type='text/javascript'>alert('Id or Password not Matched.');</script>";
        } else {
            header("location: complainer_page.php");
            exit();
        }
    } else {
        echo "<script type='text/javascript'>alert('Please fill in all fields.');</script>";
    }
}
?> 
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
    
    <script>
        function f1() {
            var sta2 = document.getElementById("exampleInputEmail1").value;
            var sta3 = document.getElementById("exampleInputPassword1").value;
            var x2 = sta2.indexOf(' ');
            var x3 = sta3.indexOf(' ');

            if (sta2 !== "" && x2 >= 0) {
                document.getElementById("exampleInputEmail1").value = "";
                document.getElementById("exampleInputEmail1").focus();
                alert("Space Not Allowed");
            } else if (sta3 !== "" && x3 >= 0) {
                document.getElementById("exampleInputPassword1").value = "";
                document.getElementById("exampleInputPassword1").focus();
                alert("Space Not Allowed");
            }
        }
    </script>
    
    <title>Complainant Login</title>
</head>
<body style="background-size: cover; background-image: url(regi_bg.jpeg); background-position: center;">
    <nav class="navbar navbar-default navbar-fixed-top" style="height: 60px;">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="home.php" style="margin-top: 5%;"><b>Crime Portal</b></a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active" style="margin-top: 5%;"><a href="userlogin.php">Complainer Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div align="center">
        <div class="form" style="margin-top: 15%">
            <form method="post">
                <div class="form-group" style="width: 30%">
                    <label for="exampleInputEmail1"><h1 style="color: #fff;">User Id</h1></label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter Email id" required name="email" onfocusout="f1()">
                </div>
                <div class="form-group" style="width:30%">
                    <label for="exampleInputPassword1"><h1 style="color: #fff;">Password</h1></label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" required name="password" onfocusout="f1()">
                </div>
                <button type="submit" class="btn btn-primary" name="s" onclick="f1()">Submit</button>
            </form>
        </div>
    </div>

    <div style="position: fixed; left: 0; bottom: 0; width: 100%; background-color: rgba(0,0,0,0.7); color: white; text-align: center;">
        <h4 style="color: white;">&copy <b>Crime Portal 2018</b></h4>
    </div>
</body>
</html>
