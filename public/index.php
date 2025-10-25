<?php include('../functions/db_connection.php') ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student DBMS</title>
    <link rel="stylesheet" href="../assets/css/indexstyles.css"> 
</head>
<body>
    <header class="navbar" style="position: sticky;">
        <div class="logo-container">
            <img src="../assets/images/cvsulogo.png" alt="CvSU Logo" class="logo-image">
            <span class="logo-text">Student Database Management System</span>
        </div>
        <div class="nav-container">           
            <nav class="menu">
                <h4><a href="#" id="login-btn">Login</a></h4>
            </nav>
            <nav class="menu">
                <h4><a href="#" id="register-btn">Register</a></h4>
            </nav>
        </div>
    </header>
    
    <img src="../assets/images/cvsu.jpg" alt="CvSU Bacoor" style="width: 100%">

    <div class="modal" id="login-modal">
        <div class="modal-content">
            <button class="close-modal" id="close-modal">&times;</button>
            <h2>Login</h2>
            <form action="../functions/login.php" method="post">
                <input type="text" name="id_number" placeholder="Student Number or ID Number" required>
                <input type="password" id="login-password" name="password" placeholder="Password" required>
                <input type="submit" value="Login">
                <div class="show-password-container">
                    <input type="checkbox" id="show-login-password"> Show Password
                </div>
            </form>    

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="modal" id="register-modal">
        <div class="modal-content">
            <button class="close-modal" id="close-register-modal">&times;</button>
            <h2>Register</h2>
            <form action="../functions/register.php" method="post">
                <label for="name">Full Name:</label>
                <input type="text" name="name" id="register-name" placeholder="Full Name" required>

                <label for="id_number">Account Number:</label>
                <input type="text" name="id_number" id="register-student-number" placeholder="Account Number" required>

                <label for="email">Email Address:</label>
                <input type="text" name="email" id="register-email" placeholder="Email Address" required>

                <input type="password" id="register-password" name="password" placeholder="Password" required>
                <label for="role">Role: </label>
                <select name="role">
                    <option name="role" id="student"  value="student">Student</option>
                    <option name="role" id="instructor"  value="instructor">Instructor</option>
                </select>


                <input type="submit" value="Register">


                <div class="show-password-container">
                    <input type="checkbox" id="show-register-password"> Show Password
                </div>

            </form>
        </div>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>
