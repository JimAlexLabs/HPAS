<?php
session_start();

// Database connection
$con = mysqli_connect("localhost", "root", "", "myhmsdb");

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to display doctors
function display_docs() {
    global $con;
    $query = "SELECT username FROM doctb";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_array($result)) {
        $name = $row['username'];
        echo '<option value="' . $name . '">' . $name . '</option>';
    }
}

// Handle patient registration
if (isset($_POST['patsub1'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if ($password == $cpassword) {
        // Insert patient data into the database
        $query = "INSERT INTO patreg (fname, lname, gender, email, contact, password, cpassword) 
                  VALUES ('$fname', '$lname', '$gender', '$email', '$contact', '$password', '$cpassword')";
        $result = mysqli_query($con, $query);

        if ($result) {
            // Set session variables
            $_SESSION['username'] = $fname . " " . $lname;
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            $_SESSION['gender'] = $gender;
            $_SESSION['contact'] = $contact;
            $_SESSION['email'] = $email;

            // Redirect to admin panel
            header("Location: admin-panel.php");
            exit();
        } else {
            die("Error: " . mysqli_error($con));
        }
    } else {
        header("Location: error1.php");
        exit();
    }
}

// Handle payment status update
if (isset($_POST['update_data'])) {
    $contact = $_POST['contact'];
    $status = $_POST['status'];

    $query = "UPDATE appointmenttb SET payment='$status' WHERE contact='$contact'";
    $result = mysqli_query($con, $query);

    if ($result) {
        header("Location: updated.php");
        exit();
    } else {
        die("Error: " . mysqli_error($con));
    }
}

// Handle doctor addition
if (isset($_POST['doc_sub'])) {
    $name = $_POST['name'];

    $query = "INSERT INTO doctb (username) VALUES ('$name')";
    $result = mysqli_query($con, $query);

    if ($result) {
        header("Location: adddoc.php");
        exit();
    } else {
        die("Error: " . mysqli_error($con));
    }
}

// Display admin panel
function display_admin_panel() {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Panel</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <h1>Admin Panel</h1>
            <!-- Add your admin panel content here -->
        </div>
    </body>
    </html>';
}
?>