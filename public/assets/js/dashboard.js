const createProjectForm = document.querySelector('form#project');
createProjectForm.addEventListener('submit', e => {
    e.preventDefault();
    const data = formDataToJSON(new FormData(createProjectForm));

    apiUse('post', '/project', data);
})

const deleteProject = id => {
    if (confirm('Are you sure?')) {
        apiUse('delete', `/project/${id}`);
    }
}
