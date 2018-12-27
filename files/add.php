<?php

require($_SERVER['DOCUMENT_ROOT'] . '/includes/init.php');

$project_id = check_data($_GET['project_id'], false, '', true);
$existing_project = sql_select('projects', 'id', "owner_id='{$_SESSION['id']}'  AND id='{$project_id}'", false);
if ($existing_project->num_rows > 0) {
    redirect('/project', 'You already have a project with this name.');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken'], '/project');

    $file_name = check_data($_POST['name'], true, 'File name', true, '/files/add');
    $file_type = check_data($_POST['type'], true, 'File type', true, '/files/add');

    $file_name = strtolower($file_name);
    $file_name = str_replace(' ', '_', $file_name);

    if ($file_type != '.html' && $file_type != '.css' && $file_type != '.js') {
        redirect('/files/add?project_id=' . $project_id, 'Incorrect file type.');
    }

    $file_full = $file_name . $file_type;

    $existing_file = sql_select('files', 'id', "owner_id='{$_SESSION['id']}'  AND project_id='{$project_id}'  AND name='{$file_full}'", false);
    if ($existing_file->num_rows > 0) {
        redirect('/files/add?project_id=' . $project_id, 'You already have a file with this name.');
    }

    sql_insert('files', [
        'owner_id' => $_SESSION['id'],
        'project_id' => $project_id,
        'name' => $file_full
    ]);

    fopen("users/{$_SESSION['username']}/{$project['name']}/{$file_full}", "w");
    fclose("users/{$_SESSION['username']}/{$project['name']}/{$file_full}");

    redirect('/home?project_id=' . $project_id, 'File created');
}

page_header('Create File');

?>

<style>
    .input-field input:focus + label {
        color: #2962FF !important;
    }

    .input-field input:focus {
        border-bottom: 1px solid #2962FF !important;
        box-shadow: 0 1px 0 0 #2962FF !important;
    }

    [type="radio"]:checked + label::after, [type="radio"].with-gap:checked + label::after {
        background-color: #2962FF !important;
    }
    [type="radio"]:checked + label::after, [type="radio"].with-gap:checked + label::before, [type="radio"].with-gap:checked + label::after {
        border: 2px solid #2962FF !important;
    }
</style>
<div class="row">
    <h4>Create Client</h4>
    <form method="post" action="?project_id=<?= $project_id ?>">
        <div class="row">
            <div class="input-field col s12">
                <label for="name">File Name</label> <input id="name" name="name" type="text">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h5>File Type</h5>
                <p>
                    <label>
                        <input checked name="type" type="radio" value=".html"> <span>HTML</span>
                    </label>
                </p>
                <p>
                    <label>
                        <input name="type" type="radio" value=".css"> <span>CSS</span>
                    </label>
                </p>
                <p>
                    <label>
                        <input name="type" type="radio" value=".js"> <span>JS</span>
                    </label>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>"/>
                <button class="col s12 btn waves-effect blue accent-4" type="submit">Create File</button>
            </div>
        </div>
    </form>
</div>

<?= page_footer(); ?>
