const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');
const socialRegister = document.getElementById('social-register');
const socialLogin = document.getElementById('social-login');

signUpButton.addEventListener('click', () => {
    container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
    container.classList.remove("right-panel-active");
});

socialRegister.addEventListener('click', () => {
    alert('Coming Soon');
});

socialLogin.addEventListener('click', () => {
    alert('Coming Soon');
});