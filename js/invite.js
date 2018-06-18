$('#submit').click(function () {

    var CSRFtoken = $('input[name=CSRFtoken]');

	var data = 'CSRFtoken=' + CSRFtoken.val() + '&type=invite';

    var $this = $('.login'),
        $state = $this.find('button > .state');
    $this.addClass('loading');
    $state.html('Generating Invite Code');


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
                $state.html('Invite Code Generated');
                setTimeout(function () {
                    $this.removeClass('loading ok');
                    $state.html('Generate Invite Code');
                    location.reload();
                }, 500)
            } else {
                $this.addClass('error');
                $state.html('Invite Code not Generated');
                setTimeout(function () {
                    $this.removeClass('loading error');
                    $state.html('Generate Invite Code');
                }, 500)
            }
            ;
        }
    });

    return false;
});
