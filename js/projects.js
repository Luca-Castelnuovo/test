//if submit button is clicked
$('#submit').click(function () {

    //Get the data from all the fields
    var project_type = $('input[name=project_type]');
    var project_id = $('input[name=project_id]');
    var project_name = $('input[name=project_name]');
    var CSRFtoken = $('input[name=CSRFtoken]');

    //Ensure non empty inputs
    if (project_name.val() == '') {
        project_name.addClass('hightlight');
        return false;
    } else project_name.removeClass('hightlight');


    //organize the data properly
    var data = 'project_id=' + project_id.val() + '&project_name=' + project_name.val() +'&CSRFtoken=' + CSRFtoken.val() + '&type=projects' + '&project_type=' + project_type.val();
    console.log(data);

    //disabled all the text fields
    $('.text').attr('disabled', 'true');

    //start the loader
    var $this = $('.login'),
        $state = $this.find('button > .state');
    $this.addClass('loading');
    $state.html('Authenticating');

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
                $state.html('Project succesfully created!');
                setTimeout(function () {
                    window.location.replace("/home");
                }, 500)
            } else {
                //if process.php returned 0/false
                $this.addClass('error');
                $state.html('Project not succesfully created!');
                setTimeout(function () {
                    window.location.replace("/home");
                }, 1000)
            };
        }
    });

    //cancel the submit button default behaviours
    return false;
});
