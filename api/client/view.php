<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/core.php");

$api_extra_param = ['note_token' => $_GET['token']];
$api_response = api_request('view', $e, $p, $api_extra_param);
if (!$api_response['status']) {
    $_SESSION['error'] = 'Note not found!';
    header('Location: /');
    exit;
}

?>
    <!DOCTYPE html>
    <html lang="en">

    <?php head('View Note'); ?>

    <body>
        <div class="login-form">
            <form>
                <h2 class="text-center">View Note</h2>
                <?php alert(); ?>
                <div class="form-group">
                    <textarea type="text" class="form-control" placeholder="Note" autocomplete="off" rows="25"><?php echo $api_response['text']; ?></textarea>
                </div>
            </form>
        </div>
    </body>

    </html>
