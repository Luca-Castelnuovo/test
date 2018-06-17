//if submit button is clicked
$('#submit').click(function () {

    //Get the data from all the fields
    var project_id = $('input[name=project_id]');
    var file_id = $('input[name=id]');
    var file_type = $('input[name=type]');
    var file_name = $('input[name=file_name]');
    var file_lang = $('input[name=file_lang]');
    var file_content = $('textarea[name=file_content]');
    var file_delete = $('input[name=file_delete]');
    var CSRFtoken = $('input[name=CSRFtoken]');

    var success_response = '';
    var error_response = '';

    if (file_type.val() == 'add') {
        success_response = 'File succesfully created!';
        error_response = 'File not created!';
    }
    if (file_type.val() == 'delete') {
        success_response = 'File succesfully deleted!';
        error_response = 'File not deleted!';
    }
    if (file_type.val() == 'edit') {
        success_response = 'File succesfully saved!';
        error_response = 'File not saved!';
    }

    //Ensure non empty inputs
    if (file_name.val() == '') {
        file_name.addClass('hightlight');
        return false;
    } else file_name.removeClass('hightlight');

    //organize the data properly
    var data = 'CSRFtoken=' + CSRFtoken.val() + '&type=files' + '&file_type=' + file_type.val() + '&project_id=' + project_id.val() + '&file_id=' + file_id.val() +'&file_name=' + file_name.val() + '&file_lang=' + file_lang.val() + '&file_content=' + file_content.val() + '&file_delete=' + file_delete.val();
    console.log(data);

    //disabled all the text fields
    $('.text').attr('disabled', 'true');

    //start the loader
    var $this = $('.login'),
        $state = $this.find('button > .state');
    $this.addClass('loading');
    $state.html('Proccessing');

    //start the ajax
    $.ajax({
        //this is the php file that processes the data
        url: "process.php",

        //GET method is used
        type: "GET",

        //pass the data
        data: data,

        //Do not cache the page
        cache: false,

        //success
        dataType: 'JSON',
        success: function (response) {
            var success = response.status;
            if (success) {
                //if process.php returned 1/true
                $this.addClass('ok');
                $state.html(success_response);
                setTimeout(function () {
                    window.location.replace("/home?project=" + project_id.val());
                }, 500)
            } else {
                //if process.php returned 0/false
                $this.addClass('error');
                $state.html(error_response);
                setTimeout(function () {
                    window.location.replace("/home?project=" + project_id.val());
                }, 1000)
            }
            ;
        }
    });

    //cancel the submit button default behaviours
    return false;
});
