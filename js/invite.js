//if submit button is clicked
$('#submit').click(function () {

    //Get the data from all the fields
    var user_email = $('input[name=email]');
    var CSRFtoken = $('input[name=CSRFtoken]');

    //Ensure non empty inputs
    if (user_email.val() == '') {
        user_email.addClass('hightlight');
        return false;
    } else user_email.removeClass('hightlight');

    //organize the data properly
    var data = 'user_email=' + user_email.val() + '&CSRFtoken=' + CSRFtoken.val() + '&type=invite';

    //disabled all the text fields
    $('.text').attr('disabled', 'true');

    //start the loader
    var $this = $('.login'),
        $state = $this.find('button > .state');
    $this.addClass('loading');
    $state.html('Sending Invite');


    //start the ajax
    $.ajax({
        //this is the php file that processes the data
        url: "auth.php",

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
                $state.html('Invite Send!');
                setTimeout(function () {
                    window.location.replace("/home");
                }, 500)
            } else {
                //if process.php returned 0/false
                $this.addClass('error');
                $state.html('Invite not Sent!');
                setTimeout(function () {
                    $this.removeClass('loading error');
                    $state.html('Invite Sent');
                    $('.text').removeAttr('disabled');
                    $('.text').focus();
                }, 500)
            };
        }
    });

    //cancel the submit button default behaviours
    return false;
});
