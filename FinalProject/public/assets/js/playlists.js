$(document).ready(function() {
    $('#getPlaylistsBtn').click(function() {
        $.ajax({
            url: '/playlists', // PHP endpoint to retrieve playlists
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $.getJSON('spotify_playlists.json', function(response) {
                    $('#playlistList').empty(); // Clear previous playlist items
                    response.items.forEach(function(playlist) {
                        $('#playlistList').append('<li>' + playlist.name + '</li>'); // Display playlist names
                    });
                });
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
});