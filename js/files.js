//if submit button is clicked
$('#submit').click(function () {

    //Get the data from all the fields
    var project_type = $('input[name=type]');
    var project_id = $('input[name=id]');
    var project_name = $('input[name=project_name]');
    var project_delete = $('input[name=project_delete]');
    var CSRFtoken = $('input[name=CSRFtoken]');

    var success_response = '';
    var error_response = '';

    if (project_type.val() == 'add') {
        success_response = 'Project succesfully created!';
        error_response = 'Project not created!';
    } else {
        success_response = 'Project succesfully deleted!';
        error_response = 'Project not deleted!';
    }

    //Ensure non empty inputs
    if (project_name.val() == '') {
        project_name.addClass('hightlight');
        return false;
    } else project_name.removeClass('hightlight');


    //organize the data properly
    var data = 'project_id=' + project_id.val() + '&project_name=' + project_name.val() +'&CSRFtoken=' + CSRFtoken.val() + '&type=projects' + '&project_type=' + project_type.val() + '&project_delete=' + project_delete.val();
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
                    window.location.replace("/home");
                }, 500)
            } else {
                //if process.php returned 0/false
                $this.addClass('error');
                $state.html(error_response);
                setTimeout(function () {
                    window.location.replace("/home");
                }, 1000)
            };
        }
    });

    //cancel the submit button default behaviours
    return false;
});
