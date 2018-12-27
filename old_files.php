<?php

switch ($_GET['type']) {

    case 'edit':
        switch ($mode) {
        case 'html':
            $show_preview= '<iframe id=preview></iframe>';
            $mode_js = '<script src="https://cdn.lucacastelnuovo.nl/test.lucacastelnuovo.nl/js/codemirror/xml.js"></script><script src="https://cdn.lucacastelnuovo.nl/test.lucacastelnuovo.nl/js/xml.js"></script>';
            break;
        case 'css':
            $mode_js = '<script src="https://cdn.lucacastelnuovo.nl/test.lucacastelnuovo.nl/js/codemirror/css.js"></script><script src="https://cdn.lucacastelnuovo.nl/test.lucacastelnuovo.nl/js/css.js"></script><style>.CodeMirror{float:none;width:100%;}</style>';
            break;
        case 'js':
            $mode_js = '<script src="https://cdn.lucacastelnuovo.nl/test.lucacastelnuovo.nl/js/codemirror/javascript.js"></script><script src="https://cdn.lucacastelnuovo.nl/test.lucacastelnuovo.nl/js/javascript.js"></script><style>.CodeMirror{float:none;width:100%;}</style>';
            break;
        }
        break;
}

?>

<!DOCTYPE html>

<html lang="en">

<head>
    <?php
    head($title, false);
    if ($_GET['type'] == 'edit') {
        echo '<link rel="stylesheet" href="https://cdn.lucacastelnuovo.nl/test.lucacastelnuovo.nl/css/codemirror.css">';
    }
    ?>
</head>

<body>
<div class="wrapper" <?php if ($_GET['type'] == 'edit') {
        echo 'style="justify-content:flex-start;"';
    } ?>>
    <form class="login <?php if ($_GET['type'] == 'edit') {
        echo 'edit';
    } ?>" method="post">
        <div class="loader"><i class="spinner"></i></div>
        <div class="content">
            <input type="hidden" name="project_id" value="<?= clean_data($_GET['project_id']) ?>"/>
            <input type="hidden" name="type" value="<?= clean_data($_GET['type']) ?>"/>
            <input type="hidden" name="id" value="<?= clean_data($_GET['id']) ?>"/>
            <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>"/>
            <p class="title"><?= $title ?></p>
            <?php foreach ($content as $row) {
        echo $row;
    } ?>
            <button id="submit"><span class="state"><?= $button_text ?></span></button>
        </div>
    </form>
</div>
<?php
footer('files');

if ($_GET['type'] == 'edit') {
    //main
    echo '<script src="https://cdn.lucacastelnuovo.nl/test.lucacastelnuovo.nl/js/codemirror.js"></script>';

    //addons
    echo '<script src="https://cdn.lucacastelnuovo.nl/test.lucacastelnuovo.nl/js/codemirror/closetag.js"></script>';

    //modes
    echo $mode_js;

    //custom setting
    echo '<script>var contenttext = $("textarea[name=textarea]").val();myCodeMirror.getDoc().setValue(contenttext)</script>';

    //warn before closing page
    // echo "<script>$(document).ready(function(){
    //     $(window).on('beforeunload',function(){
    //         return '';
    //     });
    // });</script>";
} ?>
</body>

</html>
