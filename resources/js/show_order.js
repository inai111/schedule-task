$(document).ready(function () {
    let modalElem = document.getElementById('modalConfirmation');
    let modalDelete = new bootstrap.Modal(modalElem);

    document.querySelectorAll('.triggerDelete').forEach(elem=>{
        elem.addEventListener('click',function(event){
            modalDelete.show();
            modalElem.querySelector('form').action = elem.dataset.href;
        });
    })
})