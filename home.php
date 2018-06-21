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
<?php footer(); ?>
<script>$('.dropdown-trigger').dropdown();</script>
</body>

</html>
