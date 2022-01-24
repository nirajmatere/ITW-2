<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
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
 

<!-- /////////////////////////////////////////// -->
<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="contactus.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style> 
    input[type=submit],  input[type=reset] {
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
</head>


<body>
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
                    <li><i class="fa fa-bell" style="font-size:25px"></i></li>
                    <a href="profile.html"><i class="fas fa-user-circle" style='font-size:25px;color:rgb(255, 251, 251)'></i></a>
                </ul>
            </div>
        </nav>
    </div>

    <!-- page code goes here -->
    <div>&nbsp;</div>
    <div>&nbsp;</div>
    <div>&nbsp;</div>
    <div>&nbsp;</div>
    <div>
        <span class="text5" style="font-family:FreightTextPro, Georgia, serif;">Create Account </span><br><br>
        <hr color="brown" width="700px" style="position: relative; left: 20%;">
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
                    <div class="topic-text">Create Account</div>
  
                         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="input-box">
                        <input type="text" name="username" placeholder="Enter your name" <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
                            <!-- <input type="text" placeholder="Enter your name"> -->
                        </div><br>
                        <div class="input-box">
                            <input type="text" placeholder="Enter your email">
                        </div><br>
                        <div class="input-box">
                        <input type="password" name="password" placeholder="Enter your password" <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                      <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            <!-- <input type="password" placeholder="Enter your password"> -->
                        </div><br>
                        <div class="input-box">
                       
                      <input type="password" name="confirm_password" placeholder="Confirm password" <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                      <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                          
                        </div><br>

                       


                        <div class="input-box">
                            <label for="birthday"><b>Date of Birth</b></label><br>
                            <input type="date" id="birthdate" name="birthdate" placeholder="Enter your DOB(DD/MM/YY)"><br>

                        </div>

                        <br> <label for="number"><b>Contact Number</b></label><br>
                        <select id="mobile number">
                                <br />
                                <option value="IN +91">IN +91</option>
                                <br />
                                <option value="UK +44">UK +44</option>
                                <br />
                                <option value="US +1">US +1</option>
                                </select>
                        <input type="number" id="mobile number" min="1000000000" max="9999999999" /><br>
                      
                        <div class="button">
                            <br> <input type="submit" class=" btn-primary" value="Submit">
                                <input type="reset" class=" btn-primary" value="Reset">
                        </div>
                        <span class="signUp">   Already have an account? <a href="login.php">Log In</a></span></th>
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