$('#submit').click(function () {

    var CSRFtoken = $('input[name=CSRFtoken]');

    if (CSRFtoken.val() == '') {return false;}

    var data = 'CSRFtoken=' + CSRFtoken.val() + '&type=admin&admin_type=invite';

    $.ajax({
        url: "/process.php",
        type: "GET",
        data: data,
        cache: false,
        dataType: 'JSON',
        success: function (response) {
            var success = response.status;
            if (success) {
                setTimeout(function () {
                    location.reload();
                }, 500)
            } else {
                setTimeout(function () {
                    location.reload();
                }, 500)
            }
            ;
        }
    });

    return false;
});
