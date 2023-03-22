function loginMessage(usernameValid, passwordValid, emptyPassword){
    let usernameInvalidMsg = document.querySelector('.username-invalid-msg');
    let passwordInvalidMsg = document.querySelector('.password-invalid-msg');
    let emptyPasswordMsg   = document.querySelector('.no-password-msg');

    if(!usernameValid){
        usernameInvalidMsg.style.display = 'block';
        passwordInvalidMsg.style.display = 'none';
        emptyPasswordMsg.style.display = 'none';
    }
    else if(emptyPassword){
        usernameInvalidMsg.style.display = 'none';
        passwordInvalidMsg.style.display = 'none';
        emptyPasswordMsg.style.display   = 'block';
    }
    else if(!passwordValid){
        usernameInvalidMsg.style.display = 'none';
        passwordInvalidMsg.style.display = 'block';
        emptyPasswordMsg.style.display = 'none';
    }
    else{
        usernameInvalidMsg.style.display = 'none';
        passwordInvalidMsg.style.display = 'none';
        emptyPasswordMsg.style.display   = 'none';
    }
}

function validate(e){
    e.preventDefault();

    let username = document.querySelector('.username').value;
    let password = document.querySelector('.password').value;
    
    if(username === ''){
        return;
    }

    fetch('login_check.php?username=' + username + '&password=' + password)
        .then(function(rawResponse){
            return rawResponse.json();
        })
        .then(function(response){
            if(response['success']){
                loginMessage(response['validUsername'], response['validPassword'], response['emptyPassword'])

                if(!response['validUsername']){
                    document.querySelector('.username').select();
                }
                else if(!response['validPassword']){
                    document.querySelector('.password').select();
                }

                //User login
                if(response['validPassword']){
                const user_id = response['user']['user_id'];
                const user_role_id = response['user']['user_role_id'];
                    fetch('login_user.php?user_id=' + user_id + '&username=' + username + '&user_role_id=' + user_role_id)
                    window.location.replace('./index.php');
                }
            }
        });
}

function load(){
    document.querySelector('.login_form').onsubmit = validate;
    document.querySelector('.username-invalid-msg').style.display = 'none';
    document.querySelector('.password-invalid-msg').style.display = 'none';
    document.querySelector('.no-password-msg').style.display = 'none';
}

document.addEventListener("DOMContentLoaded", load);