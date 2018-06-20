$('#submit').click(function () {

    var CSRFtoken = $('input[name=CSRFtoken]');

    var data = 'CSRFtoken=' + CSRFtoken.val() + '&type=admin&admin_type=invite';

    var $this = $('.login'), $state = $this.find('a > .state');
    $state.html('Generating Code');

    $.ajax({
        url: "process.php",
        type: "GET",
        data: data,
        cache: false,
        dataType: 'JSON',
        success: function (response) {
            var success = response.status;
            if (success) {
                $state.html('Code Generated');
                setTimeout(function () {
                    $state.html('Generate Code');
                    location.reload();
                }, 500)
            } else {
                $state.html('Code not Generated');
                setTimeout(function () {
                    $state.html('Generate Code');
                }, 500)
            }
            ;
        }
    });

    return false;
});
