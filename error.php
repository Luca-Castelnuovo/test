<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php"); $error = clean_data($_GET['e']);?>
<!DOCTYPE html>

<html lang="en">

<?php head('Error - ' . $error); ?>

<body>
    <div class="wrapper">
       <div class="login pd-20">
            <p class="title"><?= 'Error - ' . $error ?></p>
            <a href="home">Home</a>
        </div>
    </div>
</body>

</html>
