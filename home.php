<?php require_once ($_SERVER['DOCUMENT_ROOT'] . "/functions.php"); login(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1,shrink-to-fit=no" name="viewport">

    <link href=https://lucacastelnuovo.nl/images/favicon.ico rel="shortcut icon">
    <title>
        <?= $_SESSION['user_name'] ?>
    </title>

    <link as="style" href="//fonts.googleapis.com/css?family=Open+Sans:400,700" onload='this.rel="stylesheet"' rel="preload">
    <link as="style" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" onload='this.rel="stylesheet"' rel="preload">
    <link as="style" href="/css/style.css" onload='this.rel="stylesheet"' rel="preload">
</head>

<body>
    <div class="wrapper">
        <div class="login" style="padding-bottom: 20px;">
            <?php //if (isset($_GET['project'])) {my_project($_GET['project']);} else {my_projects();} ?>
            <h2>Your Projects:</h2>
            <ul>
                <li><a href="?project=1"><b>test1</b></a> <a class='dropdown-trigger btn' href='#' data-target='dropdown1' style="float: right">Drop Me!</a></li>
                <!-- Dropdown Structure -->
                <ul id='dropdown1' class='dropdown-content'>
                    <li><a href="#!">one</a></li>
                    <li><a href="#!">two</a></li>
                    <li class="divider" tabindex="-1"></li>
                    <li><a href="#!">three</a></li>
                    <li><a href="#!"><i class="material-icons">view_module</i>four</a></li>
                    <li><a href="#!"><i class="material-icons">cloud</i>five</a></li>
                </ul>
            </ul>
            <br><a href="/?logout">Log Out</a>
        </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
    <script>$('.dropdown-trigger').dropdown();</script>
</body>

</html>
