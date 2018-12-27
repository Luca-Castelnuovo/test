//unique filename
//strtolower
//trim filename
//change space in the middle to underscore (strreplace)

// only allow
// html
// css
// js

<?php

require($_SERVER['DOCUMENT_ROOT'] . '/includes/init.php');

$project_id = check_data($_GET['project_id'], false, '', true);

//check if user has access to project

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken'], '/project');

    $file_name = check_data($_POST['name'], true, 'File name', true, '/files/add');
    $file_type = check_data($_POST['type'], true, 'File type', true, '/files/add');

    $file_name = strtolower($file_name);
    $file_name = str_replace(' ', '_', $file_name);

    $existing_project = sql_select('projects', 'id', "owner_id='{$user_id}'  AND name='{$project_name}'", false);
    if ($existing_project->num_rows > 0) {
        redirect('/project', 'You already have a project with this name.');
    }

    sql_insert('projects', [
        'owner_id' => $_SESSION['id'],
        'name' => $project_name
    ]);

    mkdir("users/{$_SESSION['username']}/{$project_name}", 0770);

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
</style>
<div class="row">
    <h4>Create Client</h4>
    <form method="post">
        <div class="row">
            <div class="input-field col s12">
                <label for="name">File Name</label> <input id="name" name="name" type="text">
            </div>
        </div>
        <div class="row">
            <h5>File Type</h5>
            <p>
                <label>
                    <input checked name="type" type="radio" value="html"> <span>HTML</span>
                </label>
            </p>
            <p>
                <label>
                    <input name="type" type="radio" value="css"> <span>CSS</span>
                </label>
            </p>
            <p>
                <label>
                    <input name="type" type="radio" value="js"> <span>JS</span>
                </label>
            </p>
        </div>
        <div class="row">
            <div class="col s12">
                <input type="hidden" name="project_id" value="<?= $_GET['project_id'] ?>"/>
                <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>"/>
                <button class="col s12 btn waves-effect blue accent-4" type="submit">Create Project</button>
            </div>
        </div>
    </form>
</div>

<?= page_footer(); ?>
