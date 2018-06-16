<?php require_once ($_SERVER['DOCUMENT_ROOT'] . "/functions.php"); login(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $_SESSION['user_name'] ?></title>
</head>
<body>
    <a href="/?logout"><h1><?= $_SESSION['user_name'] ?> user</h1></a>

    <h3>Your Projects:</h3>
    <?php ($_GET['project']) ? $_GET['project'] : null; my_projects($project); ?>
</body>
</html>
