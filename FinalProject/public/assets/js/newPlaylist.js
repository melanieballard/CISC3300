$(document).ready(function() {
    var tracks = JSON.parse(localStorage.getItem('newTracks'));
    if (Array.isArray(tracks)) {
        var content = '<ul>';
        tracks.forEach(function(track) {
            content += '<li>' + track.track + ' - ' + track.artist + '</li>';
        });
        content += '</ul>';

        $('#trackList').html(content);
    } else {
        console.log('No tracks data found in localStorage');
    }
});