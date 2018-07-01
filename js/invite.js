$("#submit").click(function () {
	var t = $("input[name=CSRFtoken]");
	if ("" == t.val()) return !1;
	var a = "CSRFtoken=" + t.val() + "&type=admin&admin_type=invite";
	return $.ajax({
		url: "/process.php",
		type: "GET",
		data: a,
		cache: !1,
		dataType: "JSON",
		success: function (t) {
			t.status;
			setTimeout(function () {
				location.reload()
			}, 500)
		}
	}), !1
});
