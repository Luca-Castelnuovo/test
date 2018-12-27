var myTextArea = document.getElementsByClassName("text")[0],
    myCodeMirror = CodeMirror(function(e) {
        myTextArea.parentNode.replaceChild(e, myTextArea)
    }, {
        lineNumbers: !0,
        mode: "javascript",
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
