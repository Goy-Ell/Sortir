window.onload = init;

//fonction retour page-n
    function goBack(n) {
    window.history.go(-n);
}

//fonction AJAX en tentative
    function init() {
        //On va chercher le lieu
        let lieu = Array.from(document.getElementsByClassName("choix-lieu"));

        console.log(lieu);

        lieu.forEach(function (elem,idx){
             elem.addEventListener('change',function (){

             })
        })

        // lieu.addEventListener('change', function (){
        //
        //     let data = {'value' : lieu.value}
        //
        //     console.log(data);
        //
        //
        //     fetch("ajax-lieu", {method: 'POST', body: JSON.stringify(data)})
        //         .then(function (response){
        //             return response.json()
        //         }).then(function (data){
        //             console.log(data);
        //     });
        // });


    }