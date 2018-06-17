//if submit button is clicked
$('#submit').click(function () {

    //Get the data from all the fields
    var CSRFtoken = $('input[name=CSRFtoken]');

    //organize the data properly
    var data = 'CSRFtoken=' + CSRFtoken.val() + '&type=invite';

    //start the loader
    var $this = $('.login'),
        $state = $this.find('button > .state');
    $this.addClass('loading');
    $state.html('Generating Invite Code');


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
                $state.html('Invite Code Generated');
                setTimeout(function () {
                    $this.removeClass('loading ok');
                    $state.html('Generate Invite Code');
                    location.reload();
                }, 500)
            } else {
                //if process.php returned 0/false
                $this.addClass('error');
                $state.html('Invite Code not Generated');
                setTimeout(function () {
                    $this.removeClass('loading error');
                    $state.html('Generate Invite Code');
                }, 500)
            };
        }
    });

    //cancel the submit button default behaviours
    return false;
});
