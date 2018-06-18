<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php"); if (isset($_GET['logout'])) {logout();} ?>
<!DOCTYPE html>

<html lang="en">

<?php head('Log In'); ?>

<body>
	<div class="wrapper">
		<form class="login" method="post">
			<p class="title">Log in</p>
			<input placeholder="Username" type="text" name="user_name" autocomplete="off" class="text" id="username" autofocus> <i class="fa fa-user"></i>
			<input placeholder="Password" type="password" name="user_password" autocomplete="off" class="text" id="password"> <i class="fa fa-key"></i>
			<input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>" />
			<button id="submit"><i class="spinner"></i> <span class="state">Log in</span></button>
		</form>
	</div>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script src="js/login.min.js"></script>
</body>

</html>
