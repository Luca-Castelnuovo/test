//if submit button is clicked
$('#submit').click(function () {

    //Get the data from all the fields
    var auth_code = $('input[name=auth_code]');
    var username = $('input[name=username]');
    var password = $('input[name=password]');
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

    if (auth_code.val() == '') {
        auth_code.addClass('hightlight');
        return false;
    } else auth_code.removeClass('hightlight');

    //organize the data properly
    var data = 'CSRFtoken=' + CSRFtoken.val() + '&type=register_auth' + '&auth_code=' + auth_code.val();

    //disabled all the text fields
    $('.text').attr('disabled', 'true');

    //start the loader
    var $this = $('.login'),
        $state = $this.find('button > .state');
    $this.addClass('loading');
    $state.html('Checking Code');


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
                $state.html('Invite Code Authorized!');
                setTimeout(function () {
                    $this.removeClass('ok loading');
                    $state.html('Submit');
                    window.location.replace("/register");
                }, 500)
            } else {
                //if process.php returned 0/false
                $this.addClass('error');
                $state.html('Invalid Invite Code!');
                setTimeout(function () {
                    $this.removeClass('error loading');
                    $state.html('Check Invite Code');
                    $('.text').removeAttr('disabled');
                }, 1000)
            };
        }
    });

    //cancel the submit button default behaviours
    return false;
});
