import '/vendor/almasaeed2010/adminlte/plugins/bs-stepper/js/bs-stepper.min.js';
import '/vendor/almasaeed2010/adminlte/plugins/bs-stepper/css/bs-stepper.min.css';
import '/vendor/almasaeed2010/adminlte//plugins/toastr/toastr.min.css';
import '/vendor/almasaeed2010/adminlte/plugins/toastr/toastr.min.js';
$(document).ready(function () {
    // buat order berarti sudah login dan email ter-verification
    let username = document.querySelector(`meta[name=username]`).content;

    var stepper = new Stepper($('.bs-stepper')[0])
    $('.prevButtonStepper').click(function(){
        stepper.previous();
    })
    $('.nextButtonStepper').click(function(){
        stepper.next();
    })

    $('#userUpdate').submit(function(e){
        e.preventDefault();

        let formElem = this;
        let body = new FormData(formElem);
        let url = formElem.action

        // reset message
        formElem.querySelectorAll('.invalid-feedback').forEach(elem=>elem.innerText='');
        // reset classList
        formElem.querySelectorAll('.is-invalid').forEach(elem=>elem.classList.remove('is-invalid'));

        axios.put(url,{
            'name': body.get('name'),
            'phone_number': body.get('phone_number'),
            'address': body.get('address'),
        })
        .then(_=>stepper.next())
        .catch(e=>{
            let data = e.response.data;
            if(data){
                if(data.message){
                    toastr.error(data.message);
                }
                if(data.errors){
                    for(const [key,val] of Object.entries(data.errors)){
                        let elem = formElem.querySelector(`[name=${key}]`);
                        if(elem){
                            elem.classList.add('is-invalid')
                            elem.closest('.form-group').querySelector('.invalid-feedback').innerText = val;
                        }
                    }
                }
            }

        })
    })

    $('#orderCreate').submit(function(e){
        e.preventDefault();

        let formElem = this;
        let body = new FormData(formElem);
        let url = formElem.action

        // reset message
        formElem.querySelectorAll('.invalid-feedback').forEach(elem=>elem.innerText='');
        // reset classList
        formElem.querySelectorAll('.is-invalid').forEach(elem=>elem.classList.remove('is-invalid'));

        axios.post(url,{
            'plan_date': body.get('plan_date'),
        })
        // .then(_=>stepper.next())
        .then(response=>{
            console.log(response.data);
            toastr.success(response.data.message);
            location.href = response.data.url_redirect;
        })
        .catch(e=>{
            let data = e.response.data;
            if(data){
                if(data.message){
                    toastr.error(data.message);
                }
                if(data.errors){
                    for(const [key,val] of Object.entries(data.errors)){
                        let elem = formElem.querySelector(`[name=${key}]`);
                        if(elem){
                            elem.classList.add('is-invalid')
                            elem.closest('.form-group').querySelector('.invalid-feedback').innerText = val;
                        }
                    }
                }
            }

        })
    })
})
