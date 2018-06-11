<?php require_once("includes/header.php"); ?>
<?php if (!$session->is_logged_in()) {redirect('login.php');} ?>

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <?php require_once("includes/top_nav.php"); ?>

    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <?php require_once("includes/side_nav.php"); ?>
</nav>

<div id="page-wrapper">
    <?php require_once("includes/admin_content.php"); ?>
</div>
<!-- /#page-wrapper -->

<?php require_once("includes/footer.php"); ?>
