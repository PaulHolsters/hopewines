function checkFormulier(){
    //window.alert("formulier wordt gecheckt");
    if(!valid()){
        window.alert("Je moet een domein en een appellatie selecteren!");
        return false;
    }
    else if(!validVoorraad()){
        window.alert("Dit formulier is niet correct ingevuld!");
        return false;
    }
    window.alert("Formulier is correct ingevuld. Klik OK om te bevestigen.");
    return true;
}

function checkItem(){
    if(!validItem()){
        window.alert("Dit formulier is niet correct ingevuld!");
        return false;
    }
    window.alert("Formulier is correct ingevuld. Klik OK om te bevestigen.");
    return true;
}

function validItem() {
    if(!valid()){
        return false;
    }
    let aantal = 0;
    if(document.getElementsByName('aantal')[0].value !== ''){
        aantal = parseInt(document.getElementsByName('aantal')[0].value);
    }
    let options = document.getElementsByClassName('product');
    for (let i = 0;i<options.length;i++){
        if(options[i].selected){
            max = parseInt(options[i].innerHTML.substring(options[i].innerHTML.lastIndexOf("-")+2,options[i].innerHTML.lastIndexOf("stuks")-1));
            return (max >= aantal);
        }
    }
    return true;
}

function checkFactuur() {
    if(
       document.getElementById('checkKlant').value === ''
    || document.getElementsByName('factuurdatum')[0].value === ''
    || document.getElementsByClassName('checkItem').length === 0
    || document.getElementsByClassName('checkAantal').length !== 0){
        window.alert("Gelieve een klant en minstens 1 item aan te maken voor deze bestelling." +
                        "\n Zorg dat voor elk product een concreet aantal is ingegeven." +
            "               \n Ben je de factuurdatum vergeten in te vullen? ");
        return false;
    }
    else{
        return true;

    }
}

function checkProef(){
    if(!validProef()){
        window.alert("Dit formulier is niet correct ingevuld!");
        return false;
    }
    window.alert("Formulier is correct ingevuld. Klik OK om te bevestigen.");
    return true;
}

function checkOnverkoopbaar(){
    if(!validOnverkoopbaar()){
        window.alert("Dit formulier is niet correct ingevuld!");
        return false;
    }
    window.alert("Formulier is correct ingevuld. Klik OK om te bevestigen.");
    return true;
}

function validOnverkoopbaar() {
    if(document.getElementsByName('actieOnverkoopbaar')[0].checked){
        return ((parseInt(document.getElementsByName('aantal')[0].value) >=  parseInt(document.getElementsByName('onverkoopbaar')[0].value))
            &&(document.getElementsByName('onverkoopbaar')[0].value !==""));
    }
    else if(document.getElementsByName('actieOnverkoopbaar')[1].checked){
        return ((parseInt(document.getElementsByName('aantalOnverkoopbaar')[0].value) >= parseInt(document.getElementsByName('onverkoopbaar')[0].value))
            &&(document.getElementsByName('onverkoopbaar')[0].value !==""));
    }
    else return !(parseInt(document.getElementsByName('onverkoopbaar')[0].value) > 0);
}

function validProef() {
    if(document.getElementsByName('actieProef')[0].checked){
        return ((parseInt(document.getElementsByName('aantal')[0].value) >=  parseInt(document.getElementsByName('proef')[0].value))
                &&(document.getElementsByName('proef')[0].value !==""));
    }
    else if(document.getElementsByName('actieProef')[1].checked){
        return ((parseInt(document.getElementsByName('aantalProef')[0].value) >= parseInt(document.getElementsByName('proef')[0].value))
            &&(document.getElementsByName('proef')[0].value !==""));
    }
    else return !(parseInt(document.getElementsByName('proef')[0].value) > 0);
}

function checkFormulier2(){
    if(!valid()){
        window.alert("Je moet een domein en een appellatie selecteren!");
        return false;
    }
    window.alert("Formulier is correct ingevuld. Klik OK om te bevestigen.");
    return true;
}

function resetActie(type) {
    if(type === "voorraad"){
        document.getElementsByName('actieVoorraad')[0].checked = false;
        document.getElementsByName('actieVoorraad')[1].checked = false;
        document.getElementsByName('voorraad')[0].value = "";
    }
    else if(type === "proef"){
        document.getElementsByName('actieProef')[0].checked = false;
        document.getElementsByName('actieProef')[1].checked = false;
        document.getElementsByName('proef')[0].value = "";
    }
    else if(type === "onverkoopbaar"){
        document.getElementsByName('actieOnverkoopbaar')[0].checked = false;
        document.getElementsByName('actieOnverkoopbaar')[1].checked = false;
        document.getElementsByName('onverkoopbaar')[0].value = "";
    }
}

function validVoorraad() {
    //window.alert("validvoorraad functie ingegaan");
    if(document.getElementsByName('aantal')[0].value !== ""){
        if(document.getElementsByName('actieVoorraad')[0].checked){
            return ((parseInt(document.getElementsByName('voorraad')[0].value) >= 0)&&(document.getElementsByName('voorraad')[0].value !==""));
        }
        else if(document.getElementsByName('actieVoorraad')[1].checked){
            return ((parseInt(document.getElementsByName('voorraad')[0].value) <= parseInt(document.getElementsByName('aantal')[0].value))
                    &&(document.getElementsByName('voorraad')[0].value !==""));
        }
        else return !(parseInt(document.getElementsByName('voorraad')[0].value) > 0);
    }
    return true;
}

function stopFilter(type) {
    if(type==='appellatie'){
        document.getElementById('appellatieFilter').value = "";
        let optionsArray = document.getElementsByClassName('appellatie');
        for (let i=0;i<optionsArray.length;i++){
                optionsArray[i].style.display = "block";
            }
        }
    else if(type==='domein'){
        document.getElementById('domeinFilter').value = "";
        let optionsArray = document.getElementsByClassName('domein');
        for (let i=0;i<optionsArray.length;i++){
            optionsArray[i].style.display = "block";
        }
    }
    else if(type==='klant'){
        document.getElementById('klantFilter').value = "";
        let optionsArray = document.getElementsByClassName('klant');
        for (let i=0;i<optionsArray.length;i++){
            optionsArray[i].style.display = "block";
        }
    }
    else if(type==='product'){
        document.getElementById('productFilter').value = "";
        let optionsArray = document.getElementsByClassName('product');
        for (let i=0;i<optionsArray.length;i++){
            optionsArray[i].style.display = "block";
        }
    }
}

function filter(type) {
    if(type==='appellatie'){
        let filterTekst = document.getElementById('appellatieFilter').value;
        let optionsArray = document.getElementsByClassName('appellatie');
        for (let i=0;i<optionsArray.length;i++){
            if(checkFilter(optionsArray[i].innerHTML,filterTekst)){
                optionsArray[i].style.display = "block";
            }
            else{
                optionsArray[i].style.display = "none";
            }
            optionsArray[i].selected = false;
        }
    }
    else if(type==='domein'){
        let filterTekst = document.getElementById('domeinFilter').value;
        let optionsArray = document.getElementsByClassName('domein');
        for (let i=0;i<optionsArray.length;i++){
            if(checkFilter(optionsArray[i].innerHTML,filterTekst)){
                optionsArray[i].style.display = "block";
            }
            else{
                optionsArray[i].style.display = "none";
            }
            optionsArray[i].selected = false;
        }
    }
    else if(type==='klant'){
        let filterTekst = document.getElementById('klantFilter').value;
        let optionsArray = document.getElementsByClassName('klant');
        for (let i=0;i<optionsArray.length;i++){
            if(checkFilter(optionsArray[i].innerHTML,filterTekst)){
                optionsArray[i].style.display = "block";
            }
            else{
                optionsArray[i].style.display = "none";
            }
            optionsArray[i].selected = false;
        }
    }
    else if(type==='product'){
        let filterTekst = document.getElementById('productFilter').value;
        let optionsArray = document.getElementsByClassName('product');
        for (let i=0;i<optionsArray.length;i++){
            if(checkFilter(optionsArray[i].innerHTML,filterTekst)){
                optionsArray[i].style.display = "block";
            }
            else{
                optionsArray[i].style.display = "none";
            }
            optionsArray[i].selected = false;
        }
    }
}

function checkFilter(innerHTML,filter) {
    return innerHTML.toLowerCase().includes(filter.toLowerCase());
}

function valid(){
    let placeholders = document.getElementsByClassName("placeholder");
    for (let i=0;i<placeholders.length;i++){
        if(placeholders[i].selected){
            return false;
        }
    }
    return true;
}
