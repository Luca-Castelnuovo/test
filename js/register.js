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
    var data = 'user_name=' + username.val() + '&user_password=' + password.val() + '&CSRFtoken=' + CSRFtoken.val() + '&type=register';

    //disabled all the text fields
    $('.text').attr('disabled', 'true');

    //start the loader
    var $this = $('.login'),
        $state = $this.find('button > .state');
    $this.addClass('loading');
    $state.html('Registering User');


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
                $state.html('Account Created!');
                setTimeout(function () {
                    window.location.replace("/");
                }, 1000)
            } else {
                //if process.php returned 0/false
                $this.addClass('error');
                $state.html('Account not Created! Please try again!');
                setTimeout(function () {
                    $this.removeClass('loading error');
                    $state.html('Submit');
                    $('.text').removeAttr('disabled');
                    $('#user_name').focus();
                }, 1500)
            };
        }
    });

    //cancel the submit button default behaviours
    return false;
});
