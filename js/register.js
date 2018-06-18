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

    var data = 'user_name=' + username.val() + '&user_password=' + password.val() + '&CSRFtoken=' + CSRFtoken.val() + '&type=register';

    $('.text').attr('disabled', 'true');

    var $this = $('.login'),
        $state = $this.find('button > .state');
    $this.addClass('loading');
    $state.html('Registering User');


    $.ajax({
        url: "process.php",
        type: "GET",
        data: data,
        cache: false,
        dataType: 'JSON',
        success: function (response) {
            var success = response.status;
            if (success) {
                $this.addClass('ok');
                $state.html('Account Created!');
                setTimeout(function () {
                    window.location.replace("/");
                }, 1000)
            } else {
                $this.addClass('error');
                $state.html('Account not Created! Please try again!');
                setTimeout(function () {
                    window.location.replace("/register");
                }, 1000)
            }
            ;
        }
    });

    return false;
});
