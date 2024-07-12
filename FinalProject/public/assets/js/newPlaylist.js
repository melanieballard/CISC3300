$(document).ready(function() {
    var tracks = JSON.parse(localStorage.getItem('newTracks')); //get tracks for new playlist
    //check if response is array
    if (Array.isArray(tracks)) {
        var content = '<ul class="list-unstyled">';
        //append track info to list
        tracks.forEach(function(track) {
            content += '<li>' + track.track + ' - ' + track.artist + '</li>';
        });
        content += '</ul>';

        $('#trackList').html(content);
    } else {
        //handle error
        console.log('No tracks data found in localStorage');
    }
});
