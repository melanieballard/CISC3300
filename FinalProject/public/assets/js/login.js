$(document).ready(function(){
    $('#usernameForm').submit(function(event){ //username submission
        event.preventDefault();
        
        var username = $('#username').val(); //get username
        
        $.ajax({ //post to handle username function
            url: '/postUsername',
            type: 'POST',
            data: {username: username},
            success: function(data){
                console.log(data);
                window.location.href = '/login'; //redirect to login page
            },
            error: function(xhr, status, error){
                //handle errors
                alert('Error occurred: ' + error);
            }
        });
    });
});