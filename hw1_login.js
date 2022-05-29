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

document.querySelector('.username input').addEventListener('blur', checkText);
document.querySelector('.password input').addEventListener('blur', checkPassword);