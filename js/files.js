$("#submit").click(function () {
	var e = $("input[name=project_id]"),
		t = $("input[name=id]"),
		a = $("input[name=type]"),
		i = $("input[name=file_name]"),
		l = $("input[name=file_lang]:checked"),
		n = $("input[name=file_delete]"),
		o = $("input[name=CSRFtoken]"),
		r = "",
		d = "";
	if ("" == a.val()) return !1;
	if ("" == o.val()) return !1;
	if ("add" == a.val()) {
		if ("" == i.val()) return !1;
		r = "File created!", d = "File not created!"
	}
	if ("delete" == a.val() && (r = "File deleted!", d = "File not deleted!"), "edit" == a.val()) {
		var c = myCodeMirror.getValue();
		if ("" == c) return !1;
		r = "File saved!", d = "File not saved!"
	}
	var s = "CSRFtoken=" + o.val() + "&type=files&file_type=" + a.val() + "&project_id=" + e.val() + "&file_id=" + t.val() + "&file_name=" + i.val() + "&file_lang=" + l.val() + "&file_delete=" + n.val();
	console.log(s), $(".text").attr("disabled", "true");
	var u = $(".login"),
		p = u.find("button > .state");
	return $(".CodeMirror").css("visibility", "hidden"), u.addClass("loading"), p.html("Proccessing"), "edit" == a.val() ? $.ajax({
		url: "/process.php?" + s,
		type: "POST",
		data: jQuery.param({
			file_content: c
		}),
		cache: !1,
		contentType: "application/x-www-form-urlencoded; charset=UTF-8",
		success: function (t) {
			u.addClass("ok"), p.html(r), setTimeout(function () {
				window.location.replace("/home/" + e.val())
			}, 500)
		},
		error: function () {
			u.addClass("error"), p.html(d), setTimeout(function () {
				window.location.replace("/home/" + e.val())
			}, 1e3)
		}
	}) : $.ajax({
		url: "/process.php",
		type: "GET",
		data: s,
		cache: !1,
		dataType: "JSON",
		success: function (t) {
			t.status ? (u.addClass("ok"), p.html(r), setTimeout(function () {
				window.location.replace("/home/" + e.val())
			}, 500)) : (u.addClass("error"), p.html(d), setTimeout(function () {
				window.location.replace("/home/" + e.val())
			}, 1e3))
		}
	}), !1
});
