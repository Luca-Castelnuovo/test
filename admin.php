<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");login_admin(); ?>
<!DOCTYPE html>

<html lang="en">

<?php head('Admin Panel'); ?>

<body>
<div class="wrapper">
    <form class="login pd-20">
        <p class="title">Admin Panel</p>
        <?php if (isset($_SESSION['invite_response'])) {
            echo $_SESSION['invite_response'];
            unset($_SESSION['invite_response']);
        } else {
            echo '<input type="hidden" name="CSRFtoken" value="' . csrf_gen() . '"/><button id="submit"><i class="spinner"></i> <span class="state">Generate Invite Code</span></button>';
        } ?>
    </form>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="js/invite.min.js"></script>
</body>

</html>
