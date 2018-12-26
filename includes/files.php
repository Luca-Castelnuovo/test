<?php

function files_list($user_id, $project_id) {
    $user_id = check_data($user_id, true, 'User ID', true, '/home');
    $project_id = check_data($project_id, true, 'Project ID', true, '/home');

    $files = sql_select('projects', 'id,name', "owner_id='{$user_id}' AND project_id='{$project_id}'", false);

    if ($files->num_rows != 0) {
        echo '<style>.blue-icon{color:#2962ff}</style>';
        echo '<ul class="collection with-header">';
        echo '<li class="collection-header"><h4>Files</h4></li>';

        $CSRFtoken = csrf_gen();

        while ($file = $files->fetch_assoc()) {
            echo <<<HTML
            <li class="collection-item">
                <div>
                    <a href="?id={$file['id']}">
                        {$file['name']}
                    </a>
                    <a href="?id={$file['id']}&delete_file&CSRFtoken={$CSRFtoken}" class="secondary-content" onclick="return confirm('Are you sure?')">
                        <i class="material-icons blue-icon">delete</i>
                    </a>
                </div>
            </li>
HTML;
        }

        echo '</ul>';
    }
}


//delete files
function files_delete($user_id, $project_id, $file_id, $CSRFtoken) {
    csrf_val($CSRFtoken, '/home');

    $user_id = check_data($user_id, true, 'User ID', true, '/home');
    $project_id = check_data($project_id, true, 'Project ID', true, '/home');
    $file_id = check_data($file_id, true, 'File ID', true, '/home');

    $file = sql_select('projects', 'name', "owner_id='{$user_id}' AND id='{$file_id}' AND project_id='{$project_id}'", true);

    if (empty($file['name'])) {
        redirect('/home', 'File doesn\'t exist');
    }

    sql_delete('files', "owner_id='{$user_id}' AND id='{$file_id}' AND project_id='{$project_id}'");

    redirect('/home?id=' . $project_id, 'File deleted');
}
