$('#submit').click(function () {

    var auth_code = $('input[name=auth_code]');
    var CSRFtoken = $('input[name=CSRFtoken]');

    if (auth_code.val() == '') {
        auth_code.addClass('hightlight');
        return false;
    } else auth_code.removeClass('hightlight');

    var data = 'CSRFtoken=' + CSRFtoken.val() + '&type=register_auth' + '&auth_code=' + auth_code.val();

    $('.text').attr('disabled', 'true');

    var $this = $('.login'),
        $state = $this.find('button > .state');
    $this.addClass('loading');
    $state.html('Checking Code');


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
                $state.html('Invite Code Authorized!');
                setTimeout(function () {
                    $this.removeClass('loading ok');
                    $state.html('Submit');
                    window.location.replace("/register");
                }, 500)
            } else {
                $this.addClass('error');
                $state.html('Invalid Invite Code!');
                setTimeout(function () {
                    $this.removeClass('loading error');
                    $state.html('Check Invite Code');
                    $('.text').removeAttr('disabled');
                    window.location.replace("/register?reset");
                }, 1000)
            }
            ;
        }
    });

    return false;
});
