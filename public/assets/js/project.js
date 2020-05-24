const createFileForm = document.querySelector('form#file');
createFileForm.addEventListener('submit', e => {
    e.preventDefault();

    const id = document.querySelector('input#project_id').value;
    const data = formDataToJSON(new FormData(createFileForm));

    apiUse('post', `/file/${id}`, data);
})

const deleteFile = id => {
    if (confirm('Are you sure?')) {
        apiUse('delete', `/file/${id}`);
    }
}
