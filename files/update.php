<?php

require($_SERVER['DOCUMENT_ROOT'] . '/includes/init.php');

$project_id = check_data($_GET['project_id'], true, 'Project ID', true, '/home');
$file_id = check_data($_GET['file_id'], true, 'File ID', true, '/home?project_id' . $project_id);

$file = sql_select('files', 'id,name', "owner_id='{$_SESSION['id']}'  AND id='{$file_id}' AND project_id='{$project_id}'", false);
if ($file->num_rows != 1) {
    redirect('/home?project_id=' . $project_id, 'File doen\'t exist');
} else {
    $file = $file->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken'], '/project');

    //

    $file = fopen("../users/{$_SESSION['username']}/{$file['name']}/{$file_full}", "w");
    //write to file
    fclose($file);

    redirect('/home?project_id=' . $project_id, 'File updated');
}

page_header('Update File');

?>

<div class="row">
    <h4>Update File</h4>
    <form method="post" action="?project_id=<?= $project_id ?>&file_id=<?= $file_id ?>">
        <textarea name="name" rows="8" cols="80">Code Editor</textarea>
        <div class="row">
            <div class="col s12">
                <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>"/>
                <button class="col s12 btn waves-effect blue accent-4" type="submit">Create File</button>
            </div>
        </div>
    </form>
</div>

<?= page_footer(); ?>
