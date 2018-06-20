$('#submit').click(function () {

    var CSRFtoken = $('input[name=CSRFtoken]');
    var user_id = $('input[name=id]');
    var user_delete = $('input[name=delete]');
    var admin_type = $('input[name=type]');

    var data = 'CSRFtoken=' + CSRFtoken.val() + '&type=admin&admin_type=' + admin_type.val() + 'user_id= ' + user_id.val() + 'user_delete=' + user_delete.val();

    $.ajax({
        url: "process.php",
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
