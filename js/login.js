document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    // Function to save login data to sessionStorage
    function saveLoginData(email, password, customerId, companyId) {
        sessionStorage.setItem('email', email);
        sessionStorage.setItem('password', password);
        sessionStorage.setItem('managerId', customerId);
    }

     // Function to check if login data exists in sessionStorage
    function hasSavedLoginData() {
        return sessionStorage.getItem('email') && sessionStorage.getItem('password');
    }

    // Function to handle login
    function handleLogin(email, password) {
        //const url = 'http://54.199.33.241/gas_html/api/customer_login.php';
        const url = 'http://127.0.0.1/gas_system/api/manager_login.php';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'email': email,
                'password': password
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.response === 'success') {
                sessionStorage.setItem('isLoggedIn', 'true');
                saveLoginData(email, password, data.customerId, data.companyId); // Save customerId to sessionStorage
                // Redirect to homepage or perform necessary actions
                window.location.href = 'homepage.html';
            } else {
                alert('帳號或密碼有誤');
            }
        })
        .catch(error => {
            console.error('Login error:', error);
            alert('An error occurred during login.');
        });
    }

    // Check if there is saved login data and auto-login
    if (hasSavedLoginData() && !isSessionTimedOut()) {
        const savedEmail = sessionStorage.getItem('email');
        const savedPassword = sessionStorage.getItem('password');
        emailInput.value = savedEmail;
        passwordInput.value = savedPassword;
        handleLogin(savedEmail, savedPassword);
    }

    // Handle form submission
    loginForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();

        if (email && password) {
            handleLogin(email, password);
        } else {
            alert('帳號密碼欄位必填');
        }
    });
});
