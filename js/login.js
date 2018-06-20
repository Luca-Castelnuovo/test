$('#submit').click(function () {

    var username = $('input[name=user_name]');
    var password = $('input[name=user_password]');
    var CSRFtoken = $('input[name=CSRFtoken]');

    if (username.val() == '') {
        username.addClass('hightlight');
        return false;
    } else username.removeClass('hightlight');

    if (password.val() == '') {
        password.addClass('hightlight');
        return false;
    } else password.removeClass('hightlight');

    var data = 'username=' + username.val() + '&password=' + password.val() + '&CSRFtoken=' + CSRFtoken.val() + '&type=login';

    $('.text').attr('disabled', 'true');

    var $this = $('.login'),
        $state = $this.find('button > .state');
    $this.addClass('loading');
    $state.html('Authenticating');


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
                $state.html('Welcome back!');
                setTimeout(function () {
                    window.location.replace("/home");
                }, 500)
            } else {
                $this.addClass('error');
                $state.html('Username and Password did not match!');
                setTimeout(function () {
                    window.location.replace("/?logout");
                }, 1000)
            }
            ;
        }
    });

    return false;
});
