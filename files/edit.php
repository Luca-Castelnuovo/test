<?php

require $_SERVER['DOCUMENT_ROOT'] . '/includes/init.php';

loggedin();

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
    fwrite($file_open, htmlspecialchars_decode($_POST['content']));
    fclose($file_open);

    redirect('/home?project_id=' . $project_id, 'File updated');
}

$ext = pathinfo($file, PATHINFO_EXTENSION);
switch ($ext) {
    case 'html':
        $mode = 'html';
        break;

    case 'css':
        $mode = 'css';
        break;

    case 'js':
        $mode = 'javascript';
        break;

    default:
        redirect('/home?project_id=' . $project_id, 'Please delete this file');
        break;
}

page_header('Edit File');

?>

<div class="row">
    <h4>Edit File</h4>
    <form method="post" action="?project_id=<?= $project_id ?>&file_id=<?= $file_id ?>" id="form">
        <div class="row">
            <div class="col s12">
                <div id="editor" style="height: 300px;"><?= htmlspecialchars(fread($file_open, filesize($file))) ?></div>
                <textarea name="content" id="textarea" style="position:absolute;top:-9999px;left:-9999px;"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>"/>
                <a class="col s12 btn waves-effect blue accent-4" onclick="copyValue()">Update File</a>
            </div>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.2/ace.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.2/ext-emmet.js">  </script>
<script src="https://cdn.jsdelivr.net/gh/nightwing/emmet-core/emmet.min.js"></script>
<script>
    var editor = ace.edit("editor", {
        basePath: "https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.2/",
        theme: "ace/theme/monokai",
        mode: "ace/mode/<?= $mode ?>",
        maxLines: 10000,
        minLines: 30,
    });

    var Emmet = require("ace/ext/emmet");
    editor.setOption("enableEmmet", true);

    function copyValue() {
        document.querySelector("#textarea").value = editor.getValue();
        document.querySelector('#form').submit();
    }
</script>

<?= page_footer(); ?>
<?php fclose($file_open); ?>
