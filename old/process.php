<?php

if (sql("INSERT INTO files (owner_id, project_id, file) VALUES ('{$_SESSION['user_id']}', '{$project_id}', '{$file_name_lang}')")) {
    $projects = sql("SELECT project_name FROM projects WHERE id='{$project_id}'AND owner_id='{$_SESSION['user_id']}'", true);
    if (!empty($projects['project_name'])) {
        fopen("users/{$_SESSION['user_name']}/{$projects['project_name']}/{$file_name_lang}", "w");
        fclose("users/{$_SESSION['user_name']}/{$projects['project_name']}/{$file_name_lang}");

$file_content = $_POST['file_content'] . PHP_EOL;
$files = sql("SELECT file FROM files WHERE id='{$file_id}'AND owner_id='{$_SESSION['user_id']}'", true);
$projects = sql("SELECT project_name FROM projects WHERE id='{$project_id}'AND owner_id='{$_SESSION['user_id']}'", true);
$file_path_full = "users/{$_SESSION['user_name']}/{$projects['project_name']}/{$files['file']}";
$file_open = fopen($file_path_full, "w");
//$file_content = "\xEF\xBB\xBF" . $file_content;
if (fwrite($file_open, $file_content)) {
    fclose($file_path_full);
} else {
    fclose($file_path_full);
}
