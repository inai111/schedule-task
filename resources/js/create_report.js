import '/vendor/almasaeed2010/adminlte//plugins/toastr/toastr.min.css';
import '/vendor/almasaeed2010/adminlte/plugins/toastr/toastr.min.js';

$(document).ready(function () {
    let inputPic = $('input#photo')[0];
    let picPrev = $('img#photoPreview')[0];
    const reader = new FileReader();

    reader.onload = function (e) {
        picPrev.src = reader.result;
    }
    inputPic.addEventListener('change', function () {
        if (this.files[0]) {
            reader.readAsDataURL(this.files[0]);
        }
    })
    inputPic.dispatchEvent(new Event('change'));

    document.addEventListener('input', function (event) {
        if (event.target.classList.contains('totalChange')) {
            totalAllVendor();
        }
    })

    document.querySelectorAll('.select-vendor').forEach(select => select.dispatchEvent(new Event('change', { bubbles: 1 })));

    document.querySelector('#createReport').addEventListener('submit', function (event) {
        event.preventDefault();

        // hilangkan invalid message
        document.querySelectorAll('#createReport .select-vendor , #createReport [name]:not(meta)')
            .forEach(elem => {
                elem.classList.remove('is-invalid');
            })

        this.querySelector('[type=submit]').disabled = 1;
        this.querySelector('[type=submit]').insertAdjacentHTML('beforeend', `
        <div class="spinner spinner-grow spinner-grow-sm ml-2" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        `);

        axios.post(this.action, new FormData(this))
            .then(response => {
                console.log(response)
                if (response.status === 201) {
                    toastr.success(response.data.message);
                    setTimeout(() => {
                        location.replace(response.data.url);
                    }, 3000)
                }
            })
            .catch(error => {
                console.log(error)
                if (error.response.status === 422) {
                    let data = error.response.data;
                    let errors = data.errors;
                    toastr.error(data.message);
                    for (const [idx, val] of Object.entries(errors)) {
                        let inputEl = document.querySelector(`[name="${idx}"]`);

                        if (inputEl) {
                            inputEl.classList.add('is-invalid');
                            inputEl.parentElement.querySelector('.invalid-feedback').innerText = val;
                        } else if (idx == 'vendors') {
                            inputEl = document.querySelector('.select-vendor');
                            if (inputEl) {
                                inputEl.classList.add('is-invalid');
                                inputEl.parentElement.querySelector('.invalid-feedback').innerText = val;
                            }
                        } else if (idx.includes('vendors.')) {
                            let split = idx.split('.');
                            let idxs = split[1];
                            let index = split[2];

                            inputEl = document.querySelectorAll(`[name="vendors[${idxs}][]"]`)[index];
                            if (inputEl) {
                                inputEl.classList.add('is-invalid');
                                let title = idxs.replace('_', ' ');
                                let text = val[0];
                                inputEl.parentElement.querySelector('.invalid-feedback').innerText = text.replace(idx, title);
                            }
                        }
                        console.log(idx, val, inputEl);
                    }
                    console.log(data)
                }
                this.querySelector('[type=submit]').disabled = 0;
                document.querySelectorAll('.spinner').forEach(spinner => spinner.remove());
            })

    });

    const rupiah = (number) => {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR"
        }).format(number);
    }

    window.delRow = function (e) {
        let row = e.target.closest('tr');
        row.remove();
        totalAllVendor();
    }

    let total = 0;
    function totalAllVendor() {
        total = 0;
        document.querySelectorAll('.totalChange')
            .forEach(vendor => {
                total += Number(vendor.value);
            });

        if (!isNaN(total)) {
            total = rupiah(total);
        } else {
            total = 'Not A Number'
        }
        document.getElementById('vendorTotal').innerText = total;
    }

    document.querySelector('#addVendor').addEventListener('click', function () {
        let select = this.parentNode.querySelector('select');

        axios.get(`/vendor/${select.value}`)
            .then(response => {

                let tableBody = document.querySelector('.vendor-table');
                let row = document.createElement('tr');

                row.insertAdjacentHTML('beforeend', `
                <td>
                    <button type="button" onclick="delRow(event)" class="btn btn-sm btn-danger btn-delete">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
                <td>
                    <input type="hidden" name="vendors[id][]" value="${response.data.id}">
                    <input type="text" class="form-control" value="${response.data.name}"
                        readonly>
                </td>
                <td>
                    <input type="text" class="form-control" value="${response.data.category.name}" readonly>
                </td>
                <td>
                    <input type="text" class="form-control totalChange" name="vendors[total_price][]"
                    value="">
                    <div class="invalid-feedback"></div>
                </td>
                <td>
                    <textarea class="form-control" name="vendors[note][]" style="resize:none" placeholder="Note ..." rows="3"></textarea>
                    <div class="invalid-feedback"></div>
                </td>
                `);
                tableBody.insertAdjacentElement('beforeend', row);
            })
            .catch(error => {
                toastr.error("Vendor is not available");
            });
    })

    document.querySelector('#vendorForm').addEventListener('submit', function (e) {
        e.preventDefault();
        this.querySelectorAll(`[name]`).forEach(input => input.classList.remove('is-invalid'));


        axios.post(this.action, new FormData(this))
            .then(response => {
                if (response.status === 201) {
                    toastr.success(response.statusText);
                    location.reload();
                }
            })
            .catch(error => {
                if (error.response.data.message) {
                    toastr.error(error.response.data.message);
                }
                if (error.response.status === 422) {
                    let data = error.response.data;
                    let errors = data.errors;
                    for (const [idx, val] of Object.entries(errors)) {
                        console.log(idx, val)
                        let errText = val[0];
                        let inputEl = this.querySelector(`[name=${idx}]`);

                        if (inputEl) {
                            inputEl.classList.add('is-invalid');
                            inputEl.parentElement.querySelector('.invalid-feedback').innerText = errText;
                        }
                    }
                }
            })
    })
})