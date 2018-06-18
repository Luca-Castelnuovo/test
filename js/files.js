//if submit button is clicked
$('#submit').click(function () {

    //Get the data from all the fields
    var project_id = $('input[name=project_id]');
    var file_id = $('input[name=id]');
    var file_type = $('input[name=type]');
    var file_name = $('input[name=file_name]');
    var file_lang = $('input[name=file_lang]:checked');
    var file_delete = $('input[name=file_delete]');
	var file_content_html = $("textarea[name='file_content']");
    var CSRFtoken = $('input[name=CSRFtoken]');

    var success_response = '';
    var error_response = '';

    $('.text').attr('disabled', 'true');

    var data = 'CSRFtoken=' + CSRFtoken.val() + '&type=files' + '&file_type=' + file_type.val() + '&project_id=' + project_id.val() + '&file_id=' + file_id.val() +'&file_name=' + file_name.val() + '&file_lang=' + file_lang.val() +  '&file_delete=' + file_delete.val();
    console.log(data);

	var $this = $('.login'),$state = $this.find('button > .state');
    $this.addClass('loading');
    $state.html('Proccessing');

    if (file_type.val() == 'add') {
		if (file_name.val() == '') {
			file_name.addClass('hightlight');
			return false;
		} else file_name.removeClass('hightlight');
        success_response = 'File created!';
        error_response = 'File not created!';
    }
    if (file_type.val() == 'delete') {
        success_response = 'File deleted!';
        error_response = 'File not deleted!';
    }
    if (file_type.val() == 'edit') {
		if (file_content_html.val() == '') {
			file_content_html.addClass('hightlight');
			return false;
		} else file_content_html.removeClass('hightlight');
		success_response = 'File saved!';
        error_response = 'File not saved!';
    }

	if (file_type.val() == 'edit') {
		$.ajax({
			url: "process.php?" + data,
			type: 'POST',
			data: jQuery.param({file_content: file_content_html.val()}),
			cache: false,
			contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
			success: function (response) {
				$this.addClass('ok');
				$state.html(success_response);
				setTimeout(function () {
					window.location.replace("/home?project=" + project_id.val());
				}, 500)
			},
			error: function () {
				$this.addClass('error');
				$state.html(error_response);
				setTimeout(function () {
						window.location.replace("/home?project=" + project_id.val());
				}, 1000)
			}
		});
	} else {
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
					$state.html(success_response);
					setTimeout(function () {
						window.location.replace("/home?project=" + project_id.val());
					}, 500)
				} else {
					$this.addClass('error');
					$state.html(error_response);
					setTimeout(function () {
						window.location.replace("/home?project=" + project_id.val());
					}, 1000)
				}
				;
			}
		});
	}

    //cancel the submit button default behaviours
    return false;
});
