function onYear(json){
    const results=json.Results;
    const box= document.querySelector("#box");
    const form= document.querySelector("#form_anno");
    const anno= document.querySelector("#anno");
    const marca= document.querySelector("#marca h2");
    if (results.length == 0) {
        const span= document.querySelector("#form_anno span");
        const error= document.createElement("span");
        if(span){ 
            span.innerHTML='';
            span.textContent = 'Nessun elemento trovato!';
        }
        else{
            error.textContent= 'Nessun elemento trovato!';
        }
        form.appendChild(error);
    }
    else{
        console.log(results);
        box.removeChild(form);
        const libreria= document.createElement("div");
        const h1= document.createElement("h1");
        h1.textContent= "Seleziona un modello";
        libreria.id= "modelli";
        libreria.classList.add("scroll");
        for(let result of results){
            const a= document.createElement("a");
            a.textContent= result.Model_Name;
            URL= "hw1_specifiche_auto.php?anno=" + encodeURIComponent(anno.value) + "&modello=" + encodeURIComponent(a.textContent) + "&marca=" + encodeURIComponent(marca.textContent);
            a.href= URL;
            libreria.appendChild(a);
        }
        box.appendChild(h1);
        box.appendChild(libreria);
    }
}

function cerca(event){
    event.preventDefault();
    const anno_input= document.querySelector("#anno");
    const anno_value= anno_input.value;
    const form= document.querySelector("#form_anno");
    const span= document.querySelector("#form_anno span");
    const error= document.createElement("span");
    if(span){span.innerHTML='';}
    if(isNaN(anno_value)){
        if(span){span.innerHTML='';}
        error.textContent= "Inserire un valore numerico";
        form.appendChild(error);
    }
    else if(anno_value.length==0 || anno_value.length>4 || anno_value<1995){
        if(span) {span.innerHTML='';}
        error.textContent= "Inserire un anno superiore o uguale al 1995";
        form.appendChild(error);
    }
    else{
        const marca= document.querySelector("#marca h2");
        const URL= "https://vpic.nhtsa.dot.gov/api/vehicles/getmodelsformakeyear/make/" + encodeURIComponent(marca.textContent) + "/modelyear/" + encodeURIComponent(anno_value) + "?format=json";
        fetch(URL).then(onResponse).then(onYear);
    }
}

function quit(event){
    const box= event.currentTarget.parentNode.parentNode;
    box.classList.add("hidden");
    box.innerHTML='';
    document.querySelector("body").classList.remove("no_scroll");
}

function selected_logo(event){
    const view= document.querySelector("#modal_view");
    const body= document.querySelector("body");
    body.classList.add("no_scroll");
    view.classList.remove("hidden");
    view.innerHTML='';
    const box= document.createElement("div");
    box.id="box";
    const marca= document.createElement("div");
    const img= document.createElement("img");
    const text= document.createElement("h2");
    const exit= document.createElement("button");
    exit.textContent= "x";
    exit.addEventListener('click', quit);
    text.textContent= event.currentTarget.querySelector("span").textContent;
    img.src= event.currentTarget.querySelector("img").src;
    marca.appendChild(img);
    marca.appendChild(text);
    marca.id="marca";

    const seleziona_anno= document.createElement("h1");
    seleziona_anno.textContent= "Seleziona un anno";
    const form= document.createElement("form");
    const div= document.createElement("div");
    div.id= "form_anno";
    const anno= document.createElement("input");
    anno.type= 'text';
    anno.name= 'anno';
    anno.id= 'anno';
    const invio= document.createElement("input");
    invio.type= 'submit';
    invio.value= 'cerca';
    invio.id= "invio";
    form.appendChild(anno);
    form.appendChild(invio);
    form.addEventListener('submit', cerca);
    div.appendChild(seleziona_anno);
    div.appendChild(form);

    box.appendChild(exit);
    box.appendChild(marca);
    box.appendChild(div);

    view.appendChild(box);    
}

function onJson(json) {
    console.log(json);
    const galleria = document.querySelector("#loghi");
    galleria.innerHTML = '';

    if (json.length == 0) {
        const messaggio = document.createElement('h1');
        messaggio.textContent = 'Nessun elemento trovato!';
        galleria.appendChild(messaggio);
    }

    for (let i = 0; i < max_loghi; i++) {
        const text = document.createElement("span");
        const img = document.createElement("img");
        const riquadro = document.createElement("div");
        text.textContent = json[i].Make_Name;
        const img_url = json[i].Make_url_logo;
        img.src= img_url;
        riquadro.appendChild(img);
        riquadro.appendChild(text);
        riquadro.addEventListener('click', selected_logo);
        galleria.appendChild(riquadro);
    }
}

function onResponse(response) {
    return response.json();
}

function carica_loghi() {
    fetch("hw1_carica_loghi.php").then(onResponse).then(onJson);
}

function caricamento(){
    const loghi= document.querySelector("#loghi");
    const img= document.createElement("img");
    img.src= "loaderCircleBlu.gif";
    img.classList.add("loading");
    loghi.appendChild(img);
}

const max_loghi = 30;
caricamento();
//carica loghi auto disponibili
carica_loghi();