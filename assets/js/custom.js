$(document).on('click', ".forgot_password_btn", function(event) {
    event.preventDefault();
    var button = this;
    var email = document.getElementById('forgot_email')
    email = email.value
    if(!email)
    {
        return;
    }
    button.disabled = true
    var emailData = {
        email: email
    }
    $("#reset-password-content").fadeOut('slow', function(){
        /* Send the data using post and put the results in a div */
        $.ajax({
            url: "/lolfeedback/auth/send_reset_email",
            type: 'POST',
            data: emailData,
            dataType: 'JSON',
            success: function(data){
                $("#reset-password-content").hide()
                $("#reset-password-content").html('<div class="text-center">'+data.message+'</div>')
                $("#reset-password-content").fadeIn('400', function(){});
               return;
            },
            error:function(data, jqXHR, textStatus, errorThrown){
                    $("#reset-password-content").html('An error has occured')
                
                return;
            }
        });
    });
});