$(document).ready(function () {
    let inputPic = $('input#photo')[0];
    let picPrev = $('img#photoPreview')[0];
    const reader = new FileReader();

    reader.onload = function (e) {
        picPrev.src = reader.result;
    }
    inputPic.addEventListener('change', function () {
        reader.readAsDataURL(this.files[0]);
    })

    document.addEventListener('change',function(event){
        if(event.target.classList.contains('select-vendor')){
            // get element
            let element = event.target;
            
        }
    })
})