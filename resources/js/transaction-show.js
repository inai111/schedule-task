$(document).ready(function () {
    $(`.midtransPay`).click(function(){
            // console.log('asd')
        let url = location.pathname;
        axios.get(url+'/snap')
        .then(response=>{
            if(response.status == 204){
                location.reload();
            }

            if(response.data.token){
                window.snap.pay(response.data.token);
            }
        })
        .catch(err=>{
            console.log(err.message)
        })
    })
})
