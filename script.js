function usernameMessage(usernameAvailable){
    let usernameAvailableMsg = document.querySelector('.username-available-msg');
    let usernameTakenMsg = document.querySelector('.username-taken-msg');

    if(usernameAvailable){
        usernameAvailableMsg.style.display = 'block';
        usernameTakenMsg.style.display = 'none';
    }
    else{
        usernameAvailableMsg.style.display = 'none';
        usernameTakenMsg.style.display = 'block';
    }
}

function emailMessage(emailAvailable){
    let emailAvailableMsg = document.querySelector('.email-available-msg');
    let emailTakenMsg = document.querySelector('.email-taken-msg');

    if(emailAvailable){
        emailAvailableMsg.style.display = 'block';
        emailTakenMsg.style.display = 'none';
    }
    else{
        emailAvailableMsg.style.display = 'none';
        emailTakenMsg.style.display = 'block';
    }
}

function checkUsername(e){
    let username = e.target.value;

    if(username === ''){
        return;
    }

    fetch('username.php?username=' + username)
        .then(function(rawResponse){
            return rawResponse.json();
        })
        .then(function(response){
            if(response['success']){
                usernameMessage(response['available'])

                if(!response['available']){
                    e.target.select();
                }
            }
        });
}

function checkEmail(e){
    let email = e.target.value;

    if(email === ''){
        return;
    }

    fetch('username.php?email=' + email)
        .then(function(rawResponse){
            return rawResponse.json();
        })
        .then(function(response){
            if(response['success']){
                emailMessage(response['available'])

                if(!response['available']){
                    e.target.select();
                }
            }
        });
}

function load(){
    document.querySelector('.username').onblur = checkUsername;
    document.querySelector('.email').onblur = checkEmail;
    document.querySelector('.username-available-msg').style.display = 'none';
    document.querySelector('.username-taken-msg').style.display = 'none';
    document.querySelector('.email-available-msg').style.display = 'none';
    document.querySelector('.email-taken-msg').style.display = 'none';
}

document.addEventListener("DOMContentLoaded", load);