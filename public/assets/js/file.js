const editor = ace.edit('editor', {
    basePath: 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.2/',
    theme: 'ace/theme/dracula',
    mode: `ace/mode/${window.file_ext}`,
    maxLines: 10000,
    minLines: 30,
});

const Emmet = require('ace/ext/emmet');
editor.setOption('enableEmmet', true);

document.querySelector('form#file').addEventListener('submit', e => e.preventDefault());

document.addEventListener('DOMContentLoaded', function() {
    const id = localStorage.getItem('file_id');
    const updated_at = localStorage.getItem('file_updated_at');
    const content = JSON.parse(localStorage.getItem('file_content'));
    
    const editor_id = document.querySelector('input#file_id').value;
    const editor_updated_at = window.file_updated_at;
    const editor_content = editor.getValue();

    localStorage.clear();

    if (id !== editor_id) {
        return;
    }

    if (content === editor_content) {
        return;
    }

    if (updated_at < editor_updated_at) {
        return;
    }

    editor.setValue(content);
    editor.clearSelection();
    
    alert('Restoring lost work');
  });

const save = quit => {
    const id = document.querySelector('input#file_id').value;
    const content = editor.getValue();

    localStorage.setItem('file_id', id);
    localStorage.setItem('file_updated_at', window.file_updated_at);
    localStorage.setItem('file_content', JSON.stringify(content));

    apiUse('put', `/file/${id}`, {quit, content});
}
