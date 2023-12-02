<?php

session_start();
if (!isset($_POST["name"])) {
    header('Location: index.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <div class="container">
        <h1>WELCOME to Dashboard</h1>
    </div>
</body>
</html>