$(document).ready(function(){
    $('#usernameForm').submit(function(event){
        event.preventDefault();
        
        var username = $('#username').val();
        
        $.ajax({
            url: '/postUsername',
            type: 'POST',
            data: {username: username},
            success: function(data){
                console.log(data);
                window.location.href = '/login';
            },
            error: function(xhr, status, error){
                // Handle errors
                alert('Error occurred: ' + error);
            }
        });
    });
});