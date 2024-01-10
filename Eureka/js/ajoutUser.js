role = document.getElementById("role");
filiere = document.getElementById("filiere");
filiere2 = document.getElementById("filiere2");
role.addEventListener("change", function() {
    
    if(role.value == 3){
        filiere.hidden = false;
        filiere2.hidden = false;
        filiere2.value="";
    }else{
        filiere.hidden = true;
        filiere2.hidden = true;

    }
});