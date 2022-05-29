function saved(json){
    console.log(json);
    img=document.querySelector("#salva img");
    count= document.querySelector("#saved_cars span");
    if(json.operation=="saved"){
        img.src= "saved.png";
    }else{
        img.src= "save.png";
    }
    count.textContent= json.n_car_saved;
}

function onResponse(response){
    return response.json();
}

function save_car(event){
    stato= document.querySelector("#salva img");
    marca= document.querySelector("#marca em").textContent;
    modello= document.querySelector("#modello em").textContent;
    anno= document.querySelector("#anno em").textContent;
    img= document.querySelector("#auto img").src;

    const formData = new FormData();
    formData.append('marca', marca);
    formData.append('modello', modello);
    formData.append('anno', anno);
    formData.append('img', img);

    fetch("hw1_save_car.php", {method: 'post', body: formData}).then(onResponse).then(saved);
}

const button= document.querySelector("#salva");
button.addEventListener('click', save_car);