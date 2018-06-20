<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
login(); ?>
<!DOCTYPE html>
<html lang="en">

<?php head($_SESSION['user_name']); ?>

<body>
<div class="wrapper">
    <div class="login pd-20">
        <?php if (isset($_GET['project'])) {
            my_project($_GET['project']);
        } else {
            my_projects();
        } ?>
    </div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
<script>$('.dropdown-trigger').dropdown();</script>
</body>

</html>
