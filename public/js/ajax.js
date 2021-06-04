
    window.onload = init;

    function init() {
        //On va chercher le lieu
        let lieu = document.getElementById("sortie_lieu");

        console.log(lieu);

        lieu.addEventListener('change', function (){

            let data = {'value' : lieu.value}

            console.log(data);


            fetch("ajax-lieu", {method: 'POST', body: JSON.stringify(data)})
                .then(function (response){
                    return response.json()
                }).then(function (data){
                    console.log(data);
            });
        });


    }