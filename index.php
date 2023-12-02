<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Login Form</title>
</head>

<body>
    <?php
    if (isset($_POST["submit"])) {
        //assigning for the container of the data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $errors = array();

        //VARIOUS ERROR CHECKERS
        if (empty($email) || empty($password) || empty($name)) {
            array_push($errors, "All fields are required");
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
        } elseif (strlen($password) < 8) {
            array_push($errors, "Password must be at least 8 characters");
        }

        //CHECKS FOR DUPLICATED EMAILS 
        require_once "auth.php";
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        $rowCount = mysqli_num_rows($result);
        if ($rowCount > 0) {
            array_push($errors, "Email already exists");
        }

        //design red mark errors
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        } else {
            //INSERTS THE FILLED DATA INTO SQL DATABASE
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ? , ?)";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt, "sss", $name, $email, $password);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'> You are registered successfully.</div>";
            } else {
                die("Something went wrong");
            }
        }
    }
    ?>
    <div class="container" id="container">

        <div class="form-container sign-up">

            <form action="index.php" method="post">
                <h1>Create Account</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                </div>
                <span>or use your email for registration</span>
                <input type="text" placeholder="Name" name="name">
                <input type="email" placeholder="Email" name="email">
                <input type="password" placeholder="Password" name="password">
                <button type="submit" name="submit">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action="auth.php" method="post">
                <!--PHPCODE for sign in -->
                <?php
                if (isset($_POST['login'])) {

                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    require_once "auth.php";
                    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
                    $result = mysqli_query($conn, $sql);
                    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    if ($user) {
                        if (password_verify($password, $user["password"])) {
                            session_start();
                            $_SESSION['user'] = "yes";
                            header('Location: home.php');
                            die();
                        } else {
                            echo "<div class='alert alert-danger'> Wrong password </div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'> Email already exist </div>";
                    }
                }
                ?>

                <h1>Sign In</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                </div>
                <span>or use your email & password</span>
                <input type="email" placeholder="Email" name="email">
                <input type="password" placeholder="Password" name="password">
                <a href="#">Forget Your Password?</a>
                <button type="submit" name="login">Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>