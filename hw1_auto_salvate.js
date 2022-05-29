function apri(event){
    box= event.currentTarget;
    console.log(box);

}

function onJson(json){
    console.log(json);
    galleria= document.querySelector("#galleria");
    for(let result of json){
        const box= document.createElement("a");
        box.classList.add("box");
        const img= document.createElement("img");
        const modello= document.createElement("h1");
        const marca= document.createElement("h1");
        const anno= document.createElement("h1");
        img.src= result.img;
        marca.textContent= result.marca;
        modello.textContent= result.modello;
        anno.textContent= result.anno;
        box.appendChild(img);
        box.appendChild(marca);
        box.appendChild(modello);
        box.appendChild(anno);
        URL= "hw1_specifiche_auto.php?anno=" + encodeURIComponent(anno.textContent) + "&modello=" + encodeURIComponent(modello.textContent) + "&marca=" + encodeURIComponent(marca.textContent);
        box.href= URL;
        galleria.appendChild(box);
    }
}

function onResponse(response){
    return response.json();
}

function carica(){
    fetch("hw1_carica_auto_salvate.php").then(onResponse).then(onJson);
}

carica();