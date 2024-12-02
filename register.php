<?php
    require_once "backend/database/config.php";

    $username = "";
    $password = "";
    $confirmed_password = "";

    $username_error = "";
    $password_error = "";
    $confirmed_password_error = "";

    $database = dbConnect();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $confirmed_password = trim($_POST["confirmed_password"]);

        if (empty($username)) {
            $username_error = "Username is required";
        }

        else if (!preg_match("/([a-zA-Z0-9]){8,}/", $username)) {
            $username_error = "Only letters and numbers are allowed in the username";
        }

        else {
            $sql = "SELECT username FROM users WHERE username = :username";

            if ($stmt = $database->prepare($sql)) {
                $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                $param_username = trim($_POST["username"]);
               if ($stmt->execute()) {
                   if ($stmt->rowCount() === 1) {
                       $username_error = "Username is already taken";
                   }
                   else {
                       $param_username = trim($_POST["username"]);
                   }
               }
               else {
                   echo "Something went wrong";
               }
            }

            else {
                echo "Something went wrong";
            }

            unset($stmt);
        }


        if (empty($password)) {
            $password_error = "Password is required";
        }
        else if (strlen($password) <= 8) {
            $password_error = "Password must be at least 8 characters long";
        }
        else {
            $password = $_POST["password"];
        }


        if (empty($confirmed_password)) {
            $confirmed_password_error = "Please confirm your password";
        }
        else {
            $confirmed_password = $_POST["confirmed_password"];

            if (empty($confirmed_password) && $password !== $confirmed_password) {
                $confirmed_password_error = "Passwords do not match";
            }
        }

        if (empty($username_error) && empty($password_error) && empty($confirmed_password_error)) {
            $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
            if ($stmt = $database->prepare($sql)) {
                $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

                $param_username = trim($_POST["username"]);
                $param_password = trim($_POST["password"]);

                if ($stmt->execute()) {
                    header("location: pages/login.php");
                }
                else {

                }
            }
        }
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Masterful Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheets/style.css">
</head>
<body>

    <div class="wrapper">
        <h2>Sign up to store results from the quiz</h2>
        <p>Fill out the form to register for an account!</p>
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter a username" class="form-control" <?php echo (!empty($username_error)) ? 'is-invalid' : '';?> value= "<?= $username?>">
                <span class="invalid-feedback">
                    <?php echo $username_error; ?>
                </span>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter a password" class="form-control" <?php echo (!empty($password_error)) ? 'is-invalid' : '';?> value= "<?= $password?>">
                <span class="invalid-feedback">
                    <?php echo $password_error; ?>
                </span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Enter a password" class="form-control" <?php echo (!empty($confirmed_password_error)) ? 'is-invalid' : '';?> value= "<?= $confirmed_password?>">
                <span class="invalid-feedback">
                    <?php echo $confirmed_password_error; ?>
                </span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Register">
                <input type="submit" class="btn btn-secondary" value="Reset Values">
            </div>

            <p>Already have an account? <a href="pages/login.php">Login Here!</a></p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
