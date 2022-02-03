<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!-- ////////////////////////////////////////// -->
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="contactus.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style> 
    input[type=submit]{
        color: #fff;
    font-size: 18px;
    outline: none;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    background: #3e2093;
    cursor: pointer;
    transition: all 0.3s ease;
   
  }
  </style>
<!--Clock Script-->
<script>
    function startTime() {
        const today = new Date();
        let h = today.getHours();
        let m = today.getMinutes();
        let s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('txt').innerHTML = h + ":" + m + ":" + s;
        setTimeout(startTime, 1000);
    }

    function checkTime(i) {
        if (i < 10) {
            i = "0" + i
        }; // add zero in front of numbers < 10
        return i;
    }
</script>

</head>


    <body onload="startTime()" onload="alert(message)">
    <div>
        <nav>
            <div class="logo" style="font-family: 'Cinzel', serif; text-transform: uppercase;">The Bookshelf</div>
            <div class="menu">
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="library.html">Library</a></li>
                    <li><a href="contactus.html">Contact Us</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Signup</a></li>
                  
                    <a href="profile.php"><i class="fas fa-user-circle" style='font-size:25px;color:rgb(255, 251, 251)'></i></a>

                </ul>
            </div>
        </nav>
    </div>
    <div>&nbsp;</div>
    <div>&nbsp;</div>
    <div>&nbsp;</div>
    <div>&nbsp;</div>
    <div>
        <span class="text5" style="font-family:FreightTextPro, Georgia, serif;">Log into THE BOOKSHELF</span><br><br>
        <hr color="brown" width="900px" style="position: relative; left: 20%;">
    </div>
    <div>&nbsp;</div>

    <div>
        <div class="container">
            <div class="content">
                <div class="left-side">
                    <div class="address details">
                        <i class="fas fa-book-open"></i>
                        <div class="topic">The Bookshelf</div>
                        <div class="text-one">Manisha Koranga</div>
                        <div class="text-two">Niraj Matere</div>
                    </div>
                    <div class="phone details">
                        <i class="fas fa-phone-alt"></i>
                        <div class="topic">Phone</div>
                        <div class="text-one">+91 75792 17213</div>
                        <div class="text-two">+91 90966 54721</div>
                    </div>
                    <div class="email details">
                        <i class="fas fa-envelope"></i>
                        <div class="topic">Email</div>
                        <div class="text-one">bt20cse033@iiitn.ac.in</div>
                        <div class="text-two">bt20cse138@iiitn.ac.in</div>
                    </div>
                </div>
                <div class="right-side">
                    <div class="topic-text">Login to the Bookshelf</div>
                    <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="input-box">
                        <input type="text" name="username" placeholder="Enter your username" <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                       <br> <span class="invalid-feedback"><?php echo $username_err; ?></span><br>
                            <!-- <input type="text" > -->
                        </div>
                        <div class="input-box"><br>
                        <input type="password" name="password" placeholder="Enter your password" <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $password_err; ?></span><br>
                            <!-- <input type="password" placeholder="Enter your password"> -->
                        </div>
                        <br>
                        <div class="button">
                        <input type="submit" class= "btn-primary" value="Login"><br>
                        <span class="signUp">Don't have an account? <a href="register.php">Create Account</a></span><br>
                        </div>
                       
                    </form>
                </div>
            </div>
        </div>

        <!-- Page code end -->


        <div class="fbody">
            <footer class="footer">
                <div class="fcontainer">
                    <div class="row">
                        <div class="footer-col">
                            <h4>Team</h4>
                            <ul>
                                <li><a href="#">Manisha Koranga BTCSE033</a></li>
                                <li><a href="#">Niraj Matere BTCSE138</a></li>
                            </ul>

                        </div>
                        <div class="footer-col">
                            <h4>Quick Find</h4>
                            <ul>
                                <li><a href="science.html">Science and Technology</a></li>
                                <li><a href="fictional.html">Fictional Books</a></li>
                                <li><a href="mythology.html">Mythological Books</a></li>
                                <li><a href="educational.html">Educational Books</a></li>
                                <li><a href="stories.html">Short Stories</a></li>
                            </ul>

                        </div>
                        <div class="footer-col">
                            <h4>Follow us</h4>
                            <div class="social-links">
                                <a href="#"><i class="fab fa-instagram"></i></a>
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                            <div class="footer-col">
                                <div id="txt" style="text-align: center; font-size: 18px; color: #bbbb;margin-top: 10px;margin-left: -14px;">
                                </div>
                            </div>

                            <div class="footer-col">
                                <div class="rig">
                                    You Read.You Learn.You Grow.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </footer>
        </div>
</body>

</html>
