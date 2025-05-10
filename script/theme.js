function switchTheme(defaultCssName) {
    const link = document.getElementById('theme-style');
    const currentTheme = link.getAttribute('href');

    if (currentTheme === defaultCssName + ".css") {
        link.setAttribute('href', defaultCssName + "-dark" + ".css");
        localStorage.setItem('theme', 'dark')
    } else {
        link.setAttribute('href', defaultCssName + ".css");
        localStorage.setItem('theme', 'light')
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme');
    const link = document.getElementById('theme-style');

    const currentTheme = link.getAttribute('href');
    let split = currentTheme.split('.');
    split.pop();
    let finalName = split.join("."); 

    if (savedTheme == "dark") {
        switchTheme(finalName);
    }
});