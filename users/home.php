<?php require_once ($_SERVER['DOCUMENT_ROOT'] . "/functions.php"); login(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $_SESSION['user_name'] ?></title>
</head>
<body>
    <h1><?= $_SESSION['user_name'] ?> user</h1>
    <a href="/?logout">Logout</a>
</body>
</html>
