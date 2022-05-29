function checkText(event){
    const input= event.currentTarget;
    if (input.value.length > 0) {
        input.parentNode.parentNode.classList.remove('errorj');
    } else {
        input.parentNode.parentNode.classList.add('errorj');
    }
}

function checkPassword(event){
    const password= document.querySelector(".password input");
    if (password.value.length > 7) {
        document.querySelector(".password span").classList.remove('errorj');
    } else {
        document.querySelector(".password span").classList.add('errorj');
    }
}

function checkConfirmPassword(event){
    const confirm_password= document.querySelector(".confirm_password input");
    if(document.querySelector(".password input").length == confirm_password.value.length > 7){
        document.querySelector(".confirm_password span").classList.remove('errorj');
    } else{
        document.querySelector(".confirm_password span").classList.add('errorj');
    }
}

function checkForm(event){
    const checkbox= document.querySelector(".allow input");
    if(checkbox.checked){
        document.querySelector(".allow span").classList.remove('errorj');
    } else {
        document.querySelector(".allow span").classList.add('errorj');
    }
}

document.querySelector('.name input').addEventListener('blur', checkText);
document.querySelector('.surname input').addEventListener('blur', checkText);
document.querySelector('.username input').addEventListener('blur', checkText);
document.querySelector('.email input').addEventListener('blur', checkText);
document.querySelector('.password input').addEventListener('blur', checkPassword);
document.querySelector('.confirm_password input').addEventListener('blur', checkConfirmPassword);
document.querySelector('.allow input').addEventListener('change', checkForm);