document.addEventListener('DOMContentLoaded', function() {

    const imageButton = document.getElementById('startGen');
    
    //tooltip
    tippy(imageButton, {
        content: '<a href="https://www.flaticon.com/free-icons/play-button" target="_blank">Play button icons created by Maxim Basinski Premium - Flaticon</a>',
        allowHTML: true,
        placement: 'bottom',
        interactive: true
    });

    //link to spotify
    imageButton.addEventListener('click', function() {
        window.location.href = "/spotify";
    });
});