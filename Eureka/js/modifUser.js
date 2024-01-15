role = document.getElementById("role");
filiere = document.getElementById("filiere");

filiere3 = document.getElementById("filiere3");
if(role.value == 3){
    filiere.hidden = false;
    filiere2.hidden = false;
    filiere.value = filiere3.value;
}
role.addEventListener("change", function() {
    
    if(role.value == 3){
        filiere.hidden = false;
        filiere2.hidden = false;
        filiere.value = filiere3.value;
    }else{
        filiere.hidden = true;
        filiere2.hidden = true;
    }
});