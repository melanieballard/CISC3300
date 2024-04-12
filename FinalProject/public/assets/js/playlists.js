$(document).ready(function() {
    $('#getPlaylistsBtn').click(function() {
        $.ajax({
            url: '/playlists', // PHP endpoint to retrieve playlists
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#playlistList').empty(); // Clear previous playlist items
                // Check if the response contains the 'items' array
                if (response.hasOwnProperty('items') && Array.isArray(response.items)) {
                    // Iterate over the 'items' array and display playlist names
                    response.items.forEach(function(playlist) {
                        $('#playlistList').append('<li>' + playlist.name + '</li>');
                    });
                } else {
                    console.error('Unexpected response format:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
});