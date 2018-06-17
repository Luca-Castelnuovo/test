<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/core.php");

login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $api_extra_param = ['note_text' => $_POST['text'], 'note_url' => $_SERVER['SERVER_NAME']];
    $api_response = api_request('share', $_SESSION['email'], $_SESSION['password'], $api_extra_param);
    if ($api_response['status']) {
        $_SESSION['api_output'] = $api_response['note_link'];
        $_SESSION['success'] = '<a href="#" onclick="copy();">Copy link and Logout</a>';
    } else {
        $_SESSION['error'] = 'Note not Saved!';
    }
}

if (isset($_GET['delete'])) {
    $api_response = api_request('delete', $_SESSION['email'], $_SESSION['password']);
    if ($api_response['status']) {
        header('Location: /?delete');
        exit;
    } else {
        $_SESSION['error'] = 'Account Not Deleted!';
    }
}

?>
    <!DOCTYPE html>
    <html lang="en">

    <?php head('Profile'); ?>

    <body>
        <div class="login-form">
            <form action="profile" method="post">
                <h2 class="text-center">Share Note</h2>
                <?php alert(); ?>
                <div class="form-group">
                    <textarea type="text" class="form-control" required="required" autocomplete="off" name="text" autofocus rows="5" placeholder="Enter note here"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Share Note</button>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-7 col-md-7">
                            <a href="profile?delete" class="btn btn-primary btn-block" onclick="return confirm('Are you sure?')">Delete Account</a>
                        </div>
                        <div class="col-12 col-sm-5 col-md-5">
                            <a href="/?logout" class="btn btn-primary btn-block" name="login">Logout</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <input type="text" value="<?php echo $_SESSION['api_output']; unset($_SESSION['api_output']); ?>" id="note">
        <script>
            function copy() {
                var copyText = document.getElementById("note");
                copyText.select();
                document.execCommand("copy");
                var elem = document.getElementById("note");
                elem.remove();
                window.location.href = "index?logout";
            }

        </script>
    </body>

    </html>
