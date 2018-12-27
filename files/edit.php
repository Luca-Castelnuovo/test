<?php

require($_SERVER['DOCUMENT_ROOT'] . '/includes/init.php');

$project_id = check_data($_GET['project_id'], true, 'Project ID', true, '/home');
$file_id = check_data($_GET['file_id'], true, 'File ID', true, '/home?project_id' . $project_id);

$file = sql_select('files', 'name', "owner_id='{$_SESSION['id']}'  AND id='{$file_id}' AND project_id='{$project_id}'", false);
if ($file->num_rows != 1) {
    redirect('/home?project_id=' . $project_id, 'File doen\'t exist');
} else {
    $file_assoc = $file->fetch_assoc();
}

$project = sql_select('projects', 'name', "owner_id='{$_SESSION['id']}'  AND id='{$project_id}'", true);
$file = fopen("../users/{$_SESSION['username']}/{$project['name']}/{$file_assoc['name']}", "r+");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken'], '/project');
    $project = sql_select('projects', 'name', "owner_id='{$_SESSION['id']}'  AND id='{$project_id}'", true);
    fwrite($file, $_POST['content'] . PHP_EOL);
    fclose($file);

    redirect('/home?project_id=' . $project_id, 'File updated');
} else {
    fclose($file);
}

page_header('Edit File');

?>

<div class="row">
    <h4>Edit File</h4>
    <form method="post" action="?project_id=<?= $project_id ?>&file_id=<?= $file_id ?>">
        <div class="row">
            <div class="col s12">
                <textarea name="content" rows="8" cols="80"><?= $file ?></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>"/>
                <button class="col s12 btn waves-effect blue accent-4" type="submit">Update File</button>
            </div>
        </div>
    </form>
</div>

<?= page_footer(); ?>
