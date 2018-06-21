$('#submit').click(function () {

    var username = $('input[name=user_name]');
    var password = $('input[name=user_password]');
    var auth_code = $('input[name=auth_code]');
    var register_type = $('input[name=type]');
    var CSRFtoken = $('input[name=CSRFtoken]');

    var success_response = '';
    var error_response = '';

    if (register_type.val() == 'invite_code') {
        if (auth_code.val() == '') {return false;}
        success_response = 'Invite Code Valid!';
        error_response = 'Invalid Invite Code!';
    }

    if (register_type.val() == 'register') {
        if (username.val() == '') {return false;}
        if (password.val() == '') {return false;}
        success_response = 'Account Created!';
        error_response = 'Username is taken. Please choose another!';
    }

    var data = 'user_name=' + username.val() + '&user_password=' + password.val() + '&auth_code=' + auth_code.val() + '&CSRFtoken=' + CSRFtoken.val() + '&type=register&register_type=' + register_type.val();

    $('.text').attr('disabled', 'true');

    var $this = $('.login'),
        $state = $this.find('button > .state');
    $this.addClass('loading');
    $state.html('Registering User');


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
                    window.location.replace("/");
                }, 1000)
            } else {
                $this.addClass('error');
                $state.html(error_response);
                setTimeout(function () {
                    window.location.replace("/register");
                }, 1000)
            }
            ;
        }
    });

    return false;
});
