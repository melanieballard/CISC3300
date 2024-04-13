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
                        $('#playlistList').append('<a href="/reccomend" class="postPlaylist"><li>' + playlist.name + '</li></a>');
                        appendToLocalStorage('playlists', playlist);
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

$(document).ready(function() {
    $('#playlistList').on('click', '.postPlaylist', function(event) {
        // Prevent default link behavior
        event.preventDefault();

        var playlistName = $(this).text(); // Get playlist name from clicked link
        var playlists = JSON.parse(localStorage.getItem('playlists'));
        var playlistId = null;
        for (var i = 0; i < playlists.length; i++) {
            if (playlists[i].name === playlistName) {
                playlistId = playlists[i].id;
                break;
            }
        }
        
        // Make AJAX POST request
        $.ajax({
            type: 'POST',
            url: '/reccomend', // Replace with the URL of your PHP script
            data: { playlistId: playlistId },
            success: function(response) {
                // Handle success response
                console.log('POST request successful:', response);
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error('Error making POST request:', error);
            }
        });
    });
});

function appendToLocalStorage(key, data) {
    let playlists = JSON.parse(localStorage.getItem(key)) || [];
    playlists.push(data);
    localStorage.setItem(key, JSON.stringify(playlists));
}