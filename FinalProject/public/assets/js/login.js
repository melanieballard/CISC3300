$(document).ready(function(){
    $('#usernameForm').submit(function(event){
        event.preventDefault();
        
        var username = $('#username').val();
        
        $.ajax({
            url: 'DataController.php',
            type: 'POST',
            data: {username: username},
            success: function(response){
                window.location.href = '/login';
            },
            error: function(xhr, status, error){
                // Handle errors
                alert('Error occurred: ' + error);
            }
        });
    });
});