<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/login.css">
        
    <title>Login</title>
</head>
<body>
    <?php
    // Unset all the server-side variables
    session_start();

    $_SESSION["user"] = "";
    $_SESSION["usertype"] = "";
    
    // Set the new timezone
    date_default_timezone_set('Asia/Kolkata');
    $_SESSION["date"] = date('Y-m-d');

    // Import database
    include("connection.php");

    // Initialize the error variable
    $error = '<label for="promter" class="form-label">&nbsp;</label>';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $database->real_escape_string($_POST['useremail']);
        $password = $database->real_escape_string($_POST['userpassword']);

        $result = $database->query("SELECT * FROM webuser WHERE email='$email'");
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $utype = $user['usertype'];

            switch ($utype) {
                case 'p':
                    $checker = $database->query("SELECT * FROM patient WHERE pemail='$email' AND ppassword='$password'");
                    break;
                case 'a':
                    $checker = $database->query("SELECT * FROM admin WHERE aemail='$email' AND apassword='$password'");
                    break;
                case 'd':
                    $checker = $database->query("SELECT * FROM doctor WHERE docemail='$email' AND docpassword='$password'");
                    break;
                default:
                    $checker = false;
            }

            if ($checker && $checker->num_rows == 1) {
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = $utype;

                switch ($utype) {
                    case 'p':
                        header('Location: patient/index.php');
                        break;
                    case 'a':
                        header('Location: admin/index.php');
                        break;
                    case 'd':
                        header('Location: doctor/index.php');
                        break;
                }
                exit;
            } else {
                $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
            }
        } else {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">We can\'t find any account with this email.</label>';
        }
    }
    ?>
    <center>
        <div class="container">
            <table border="0" style="margin: 0;padding: 0;width: 60%;">
                <tr>
                    <td>
                        <p class="header-text">Welcome Back!</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="sub-text">Login with your details to continue</p>
                    </td>
                </tr>
                <tr>
                    <form action="" method="POST">
                        <td class="label-td">
                            <label for="useremail" class="form-label">Email: </label>
                        </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <input type="email" name="useremail" class="input-text" placeholder="Email Address" required>
                    </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <label for="userpassword" class="form-label">Password: </label>
                    </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <input type="password" name="userpassword" class="input-text" placeholder="Password" required>
                    </td>
                </tr>
                <tr>
                    <td><br>
                        <?php echo $error ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="Login" class="login-btn btn-primary btn">
                    </td>
                </tr>
                </form>
                <tr>
                    <td>
                        <br>
                        <label for="" class="sub-text" style="font-weight: 280;">Don't have an account? </label>
                        <a href="signup.php" class="hover-link1 non-style-link">Sign Up</a>
                        <br><br><br>
                    </td>
                </tr>
            </table>
        </div>
    </center>
</body>
</html>
