$(document).ready(function(){
    $('#usernameForm').submit(function(event){
        event.preventDefault();
        
        var username = $('#username').val();
        
        $.ajax({
            url: 'Data.php',
            type: 'POST',
            data: {username: username},
            success: function(response){
                console.log(response);
                window.location.href = '/login';
            },
            error: function(xhr, status, error){
                // Handle errors
                alert('Error occurred: ' + error);
            }
        });
    });
});