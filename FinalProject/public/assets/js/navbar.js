fetch('/navbar')
.then(response => response.text())
.then(data => {
    document.getElementById('headerContainer').innerHTML = data;
})
.then(() => {
    // Run any scripts after including navbar (e.g., toggle theme script)
    const themeStyle = document.getElementById('colorTheme');
    const themeToggle = document.getElementById('colorToggle');
    const themeIcon = document.getElementById('theme-icon');

    const navbarToggle = document.getElementById('navbarToggle');
    const githubToggle = document.getElementById('github');
    const linkedinToggle = document.getElementById('linkedin');

    function toggleButtonClasses() {
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            if (button.classList.contains('btn-primary')|| button.classList.contains('btn-light')) {
                if (themeStyle.getAttribute('href') === 'assets/styles/styleLight.css') {
                    button.classList.remove('btn-light');
                    button.classList.add('btn-primary');
                } else {
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-light');
                }
            }
        });
    }

    function setInitialTheme() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            themeStyle.setAttribute('href', savedTheme);
            if (savedTheme === 'assets/styles/styleLight.css') {
                themeIcon.innerHTML = '<i class="fas fa-moon"></i>'; // Light mode
            } else {
                themeIcon.innerHTML = '<i class="fas fa-sun"></i>'; // Dark mode
                navbarToggle.setAttribute('class', "navbar w-100 navbar-expand-lg navbar-dark px-3 sticky-top");
                githubToggle.setAttribute('src', "assets/styles/images/githublight.svg");
                linkedinToggle.setAttribute('src', "assets/styles/images/linkedinlight.png");
                toggleButtonClasses();
            }
        } else {
            // Default to light mode if no preference is stored
            themeIcon.innerHTML = '<i class="fas fa-moon"></i>'; // Light mode
        }
    }

    function toggleTheme() {
        if (themeStyle.getAttribute('href') === 'assets/styles/styleLight.css') {
            themeStyle.setAttribute('href', 'assets/styles/styleDark.css');
            themeIcon.innerHTML = '<i class="fas fa-sun"></i>'; // Dark mode
            navbarToggle.setAttribute('class', "navbar w-100 navbar-expand-lg navbar-dark px-3 sticky-top");
            githubToggle.setAttribute('src', "assets/styles/images/githublight.svg");
            linkedinToggle.setAttribute('src', "assets/styles/images/linkedinlight.png");
            toggleButtonClasses();
            localStorage.setItem('theme', 'assets/styles/styleDark.css'); // Save preference
        } else {
            themeStyle.setAttribute('href', 'assets/styles/styleLight.css');
            themeIcon.innerHTML = '<i class="fas fa-moon"></i>'; // Light mode
            navbarToggle.setAttribute('class', "navbar w-100 navbar-expand-lg navbar-light px-3 sticky-top");
            githubToggle.setAttribute('src', "assets/styles/images/github.png");
            linkedinToggle.setAttribute('src', "assets/styles/images/linkedin.png");
            toggleButtonClasses();
            localStorage.setItem('theme', 'assets/styles/styleLight.css'); // Save preference
        }
    }

    setInitialTheme();

    themeToggle.addEventListener('click', toggleTheme);
})
.catch(error => console.error('Error fetching navbar:', error));