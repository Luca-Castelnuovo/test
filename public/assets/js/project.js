const createFileForm = document.querySelector('form#file');
createFileForm.addEventListener('submit', e => {
    e.preventDefault();
    const data = formDataToJSON(new FormData(createFileForm));

    apiUse('post', '/file', data);
})

const deleteFile = id => {
    if (confirm('Are you sure?')) {
        apiUse('delete', `/file/${id}`);
    }
}
