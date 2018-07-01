$("#submit").click(function () {
	var a = $("input[name=user_name]"),
		t = $("input[name=user_password]"),
		e = $("input[name=CSRFtoken]");
	if ("" == a.val()) return !1;
	if ("" == t.val()) return !1;
	if ("" == e.val()) return !1;
	var n = "user_name=" + a.val() + "&user_password=" + t.val() + "&CSRFtoken=" + e.val() + "&type=login";
	$(".text").attr("disabled", "true");
	var o = $(".login"),
		s = o.find("button > .state");
	return o.addClass("loading"), s.html("Authenticating"), $.ajax({
		url: "/process.php",
		type: "GET",
		data: n,
		cache: !1,
		dataType: "JSON",
		success: function (a) {
			a.status ? (o.addClass("ok"), s.html("Welcome back!"), setTimeout(function () {
				window.location.replace("/home")
			}, 500)) : (o.addClass("error"), s.html("Username and Password did not match!"), setTimeout(function () {
				window.location.replace("/?logout")
			}, 1e3))
		}
	}), !1
});
