function updatePreview() {
	var e = document.getElementById("preview"),
		t = e.contentDocument || e.contentWindow.document;
	t.open(), t.write(myCodeMirror.getValue()), t.close()
}
var delay, myTextArea = document.getElementsByClassName("text")[0],
	myCodeMirror = CodeMirror(function (e) {
		myTextArea.parentNode.replaceChild(e, myTextArea)
	}, {
		lineNumbers: !0,
		mode: "xml",
		htmlMode: !0,
		theme: "base16-dark",
		tabSize: 4,
		indentWithTabs: !0,
		lineWrapping: !0,
		historyEventDelay: 400,
		autofocus: !0,
		autoCloseTags: !0,
		viewportMargin: 1 / 0
	});
myCodeMirror.on("change", function () {
	clearTimeout(delay), delay = setTimeout("updatePreview()", 300)
}), setTimeout(updatePreview, 300);
