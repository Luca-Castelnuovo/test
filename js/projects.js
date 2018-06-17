//if submit button is clicked
$('#submit').click(function () {

    //Get the data from all the fields
    var username = $('input[name=user_name]');
    var password = $('input[name=user_password]');
    var CSRFtoken = $('input[name=CSRFtoken]');

    //Ensure non empty inputs
    if (username.val() == '') {
        username.addClass('hightlight');
        return false;
    } else username.removeClass('hightlight');

    if (password.val() == '') {
        password.addClass('hightlight');
        return false;
    } else password.removeClass('hightlight');

    //organize the data properly
    var data = 'username=' + username.val() + '&password=' + password.val() + '&CSRFtoken=' + CSRFtoken.val() + '&type=login';

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
                $state.html('Welcome back!');
                setTimeout(function () {
                    window.location.replace("/home");
                }, 500)
            } else {
                //if process.php returned 0/false
                $this.addClass('error');
                $state.html('Username and Password did not match!');
                setTimeout(function () {
                    window.location.replace("/?logout");
                }, 1000)
            };
        }
    });

    //cancel the submit button default behaviours
    return false;
});
