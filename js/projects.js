$('#submit').click(function () {

    var project_type = $('input[name=type]');
    var project_id = $('input[name=id]');
    var project_name = $('input[name=project_name]');
    var project_delete = $('input[name=project_delete]');
    var CSRFtoken = $('input[name=CSRFtoken]');

    var success_response = '';
    var error_response = '';

    if (project_type.val() == '') {return false;}
    if (CSRFtoken.val() == '') {return false;}

    if (project_type.val() == 'add') {
        if (project_name.val() == '') {return false;}
        success_response = 'Project succesfully created!';
        error_response = 'Project not created!';
    }

    if (project_type.val() == 'delete') {
        if (project_delete.val() == '') {return false;}
        if (project_id.val() == '') {return false;}
        success_response = 'Project succesfully deleted!';
        error_response = 'Project not deleted!';
    }

    var data = 'project_id=' + project_id.val() + '&project_name=' + project_name.val() + '&CSRFtoken=' + CSRFtoken.val() + '&type=projects' + '&project_type=' + project_type.val() + '&project_delete=' + project_delete.val();
    console.log(data);

    $('.text').attr('disabled', 'true');

    var $this = $('.login'), $state = $this.find('button > .state');
    $this.addClass('loading');
    $state.html('Proccessing');

    $.ajax({
        url: "/process.php",
        type: "GET",
        data: data,
        cache: false,
        dataType: 'JSON',
        success: function (response) {
            var success = response.status;
            if (success) {
                $this.addClass('ok');
                $state.html(success_response);
                setTimeout(function () {
                    window.location.replace("/home");
                }, 500)
            } else {
                $this.addClass('error');
                $state.html(error_response);
                setTimeout(function () {
                    window.location.replace("/home");
                }, 1000)
            }
            ;
        }
    });

    return false;
});
