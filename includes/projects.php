<?php

function projects_list($user_id) {
    $user_id = check_data($user_id, true, 'User ID', true, '/home');

    $projects = sql_select('projects', 'id,name', "owner_id='{$user_id}'", false);

    echo '<style>.blue-icon{color:#2962ff}</style>';
    echo '<ul class="collection with-header">';
    echo '<li class="collection-header"><h4>Projects</h4></li>';

    if ($projects->num_rows != 0) {

        $CSRFtoken = csrf_gen();

        while ($project = $projects->fetch_assoc()) {
            echo <<<HTML
            <li class="collection-item">
                <div>
                    <a href="?id={$project['id']}">
                        {$project['name']}
                    </a>
                    <a href="?id={$project['id']}&delete_project&CSRFtoken={$CSRFtoken}" class="secondary-content" onclick="return confirm('Are you sure?')">
                        <i class="material-icons blue-icon">delete</i>
                    </a>
                </div>
            </li>
HTML;
        }

    } else {
        echo '<li class="collection-item">You don\'t have any projects.</li>';
    }

    echo '</ul>';
}


function projects_delete($user_id, $project_id, $CSRFtoken) {
    csrf_val($CSRFtoken, '/home');

    $user_id = check_data($user_id, true, 'User ID', true, '/home');
    $project_id = check_data($project_id, true, 'Project ID', true, '/home');

    $project = sql_select('projects', 'name', "owner_id='{$user_id}' AND id='{$project_id}'", true);

    if (empty($project['name'])) {
        redirect('/home', 'Project doesn\'t exist');
    }

    sql_delete('projects', "owner_id='{$user_id}' AND id='{$project_id}'");

    redirect('/home', 'Project deleted');
}


function projects_info($user_id, $project_id) {
    $user_id = check_data($user_id, true, 'User ID', true, '/home');
    $project_id = check_data($project_id, true, 'Project ID', true, '/home');

    $project = sql_select('projects', 'name', "owner_id='{$user_id}' AND id='{$project_id}'", true);

    if (empty($project)) {
        redirect('/home', 'Project doesn\'t exist');
    }

    echo <<<HTML
    <div class="row">
        <h4>{$project['name']}</h4>
    </div>
    <div class="row">
HTML;
    files_list($user_id, $project_id);
echo '</div>';

}