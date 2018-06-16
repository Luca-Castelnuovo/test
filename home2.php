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
            <h2>Projects:</h2>
            <table>
                <tr>
                    <td><a class='dropdown-trigger btn' href='#' data-target='dropdown1'>test1</a></td>
                    <td><a class='dropdown-trigger btn' href='#' data-target='dropdown2'>test2</a></td>
                    <td><a class='dropdown-trigger btn' href='#' data-target='dropdown3'>test3</a></td>
                </tr>
            </table>
            <ul id='dropdown1' class='dropdown-content'>
                    <li><a href="#!">edit</a></li>
                    <li><a href="#!">delete</a></li>
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
