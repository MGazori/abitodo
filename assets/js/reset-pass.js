//reset password
$('form#reset-mail').submit(function(e) {
    e.preventDefault()
    e.stopImmediatePropagation();
    var userResetEmail = $('#user-email');
    $.ajax({
        url: "process/ajaxHandler.php",
        method: "POST",
        dataType: "JSON",
        data: {
            action: "resetPasswordSendMail",
            userEmail: userResetEmail.val()
        },
        success: function(response) {
            if (response.status == "success") {
                $('form#reset-mail').remove();
                $('#container').append(response.tokenForm)
                $('input#user-reset-token').focus();
                $('.forget-pass-msg').remove();
                checkValidToken();
            } else if (response.status == "error") {
                alert(response.description)
            }
        }
    })
})

//define function for check token validation
function checkValidToken() {
    $('form#reset-token').submit(function(e) {
        e.preventDefault()
        e.stopImmediatePropagation();
        var userResetToken = $('#user-reset-token');
        $.ajax({
            url: "process/ajaxHandler.php",
            method: "POST",
            dataType: "JSON",
            data: {
                action: "checkToken",
                userToken: userResetToken.val()
            },
            success: function(response) {
                if (response.status == "success") {
                    $('form#reset-token').remove();
                    $('#container').append(response.passForm)
                    $('input#user-reset-pass').focus();
                    resetPass();
                } else if (response.status == "error") {
                    alert(response.description)
                }
            }
        })
    })
}

//define function for  reset user passswords
function resetPass() {
    $('form#reset-pass').submit(function(e) {
        e.preventDefault()
        e.stopImmediatePropagation();
        var newPass = $('#user-reset-pass');
        var newPassAgain = $('#user-reset-pass-again');
        if (newPass.val() === newPassAgain.val()) {
            $.ajax({
                url: "process/ajaxHandler.php",
                method: "POST",
                dataType: "JSON",
                data: {
                    action: "resetPassword",
                    userNewPass: newPass.val()
                },
                success: function(response) {
                    if (response.status == "success") {
                        $('div#container').remove();
                        $('body').append("<h3 class='successfully-change-pass-msg'>Your password successfully changed</h3>");
                        setTimeout(function() { window.location.replace(response.redirectAddress) }, 2000);
                    } else if (response.status == "error") {
                        alert(response.description)
                    }
                }
            })
        } else {
            alert('password not match.')
        }
    })
}