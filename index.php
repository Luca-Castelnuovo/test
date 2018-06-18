<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php"); if (isset($_GET['logout'])) {logout();} ?>
<!DOCTYPE html>

<html lang="en">

<head>
	<meta content="Luca Castelnuovo" name=author>
	<meta content="Luca Castelnuovo is a 15 years old developer from The Netherlands" name=description>
	<meta content="Luca, Castelnuovo, young, developer, the netherlands, soest, lucacastelnuovo, techassistants soest, webdevelopment, Luca Castelnuovo, tech assistants, techassistant, tasoest, betasterren, and aannemersbedrijf" name=keywords>
	<meta content=summary name=twitter:card>
	<meta content=@LucaCastelnuovo name=twitter:site>
	<meta content=@LucaCastelnuovo property=twitter:creator>
	<meta content=https://test.lucacastelnuovo.nl property=og:url>
	<meta content="Luca Castelnuovo" property=og:title>
	<meta content="Luca Castelnuovo is a 15 years old developer from The Netherlands." property=og:description>
	<meta content=https://lucacastelnuovo.nl/images/favicon.ico property=og:image>
	<meta content=website property=og:type>
	<meta content="Luca Castelnuovo" property=og:site_name>
	<link href=https://test.lucacastelnuovo.nl rel=canonical>
	<title>Log In</title>
	<meta charset=utf-8>
	<meta content="ie=edge" http-equiv=x-ua-compatible>
	<meta content="width=device-width,initial-scale=1,shrink-to-fit=no" name=viewport>
	<link href=https://lucacastelnuovo.nl/images/favicon.ico rel="shortcut icon">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans:400,700">
</head>

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
	<script src="js/login.js"></script>
</body>

</html>
