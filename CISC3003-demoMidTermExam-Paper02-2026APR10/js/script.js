document.addEventListener('DOMContentLoaded', function () {
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    if (signUpButton && container) {
        signUpButton.addEventListener('click', function () {
            container.classList.add('right-panel-active');
        });
    }

    if (signInButton && container) {
        signInButton.addEventListener('click', function () {
            container.classList.remove('right-panel-active');
        });
    }
});
