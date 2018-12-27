<?php

require($_SERVER['DOCUMENT_ROOT'] . '/includes/init.php');

$project_id = check_data($_GET['project_id'], true, 'Project ID', true, '/home');
$file_id = check_data($_GET['file_id'], true, 'File ID', true, '/home?project_id' . $project_id);

$file_sql = sql_select('files', 'name', "owner_id='{$_SESSION['id']}'  AND id='{$file_id}' AND project_id='{$project_id}'", false);
if ($file_sql->num_rows != 1) {
    redirect('/home?project_id=' . $project_id, 'File doen\'t exist');
} else {
    $file_assoc = $file_sql->fetch_assoc();
}

$project = sql_select('projects', 'name', "owner_id='{$_SESSION['id']}'  AND id='{$project_id}'", true);
$file = "../users/{$_SESSION['username']}/{$project['name']}/{$file_assoc['name']}";
$file_open = fopen($file, "r+");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken'], '/project');
    $project = sql_select('projects', 'name', "owner_id='{$_SESSION['id']}'  AND id='{$project_id}'", true);
    fwrite($file_open, $_POST['content'] . PHP_EOL);
    fclose($file_open);

    redirect('/home?project_id=' . $project_id, 'File updated');
}

$file_extension = substr($file, strpos($file, ".") + 1);
switch ($file_extension) {
    case 'html':
        $content = <<<HTML
        <textarea name="textarea" class="secret">{$file_content}</textarea>
        <div style="display: flex;">
            <textarea name="content" class="text" id="code" placeholder="Enter your code here..."></textarea>
            <iframe id=preview></iframe>
        </div>
HTML;
        $content_js = '<script src="/js/editor/lib/xml.js"></script><script src="/js/editor/init/xml.js"></script>';
        break;

    default:
        redirect('/home?project_id=' . $project_id, 'Please delete this file');
        break;
}

$file_content = fread($file_open, filesize($file));

page_header('Edit File');

?>

<link rel="stylesheet" href="/js/editor/main.css">
<link rel="stylesheet" href="/js/editor/editor.css">
<div class="row">
    <h4>Edit File</h4>
    <form method="post" action="?project_id=<?= $project_id ?>&file_id=<?= $file_id ?>">
        <div class="row">
            <div class="col s12">
                <?= $content ?>
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
<script src="/js/editor/init.js"></script>
<script src="/js/editor/plugins/closetag.js"></script>
<?= $content_js ?>
<script>var contenttext = document.querySelector("textarea[name=textarea]").value;myCodeMirror.getDoc().setValue(contenttext)</script>

<?= page_footer(); ?>
<?php fclose($file_open); ?>
