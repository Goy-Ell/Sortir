window.onload = init;

//fonction retour page-n
    function goBack(n) {
    window.history.go(-n);
}
//popup de confirmation de suppression d'un utilisateur
    function confirmeSupUser(){
        window.confirm("Voulez vous vraiment supprimer cet utilisateur ?")
    }

