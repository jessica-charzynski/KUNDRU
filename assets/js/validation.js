document.addEventListener('DOMContentLoaded', function () {
    // Function to display error messages below the respective field
    function displayFieldErrors(field, message) {
        var feedback = document.getElementById(field.id + 'Feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.id = field.id + 'Feedback';
            field.parentElement.appendChild(feedback);
        }
        feedback.textContent = message;
    }

    // Function to display a general error message below the form
    function displayGeneralErrors(message) {
        var formFeedback = document.getElementById('formFeedback');
        if (formFeedback) {
            formFeedback.textContent = message;
            formFeedback.style.display = 'block';
        }
    }

    // Function to clear error messages for a field
    function clearFieldErrors(field) {
        field.classList.remove('is-invalid');
        var feedback = document.getElementById(field.id + 'Feedback');
        if (feedback) {
            feedback.textContent = '';
        }
    }

    // Function to validate login fields
    function validateLoginField(field) {
        var isValid = true;
        var errorMessage = '';

        if (field.id === 'email') {
            if (field.value.trim() === '') {
                errorMessage = 'Das Feld E-Mail-Adresse darf nicht leer sein.';
                isValid = false;
            } else if (!/.+@.+\..+/.test(field.value.trim())) {
                errorMessage = 'Bitte geben Sie eine gültige E-Mail-Adresse ein.';
                isValid = false;
            }
        } else if (field.id === 'password') {
            if (field.value.trim() === '') {
                errorMessage = 'Das Feld Passwort darf nicht leer sein.';
                isValid = false;
            } else if (field.value.length < 8) {
                errorMessage = 'Passwort muss mindestens 8 Zeichen lang sein.';
                isValid = false;
            }
        }

        if (isValid) {
            clearFieldErrors(field);
        } else {
            field.classList.add('is-invalid');
            displayFieldErrors(field, errorMessage);
        }
    }

    // Function to validate registration fields
    function validateRegisterField(field) {
        var isValid = true;
        var errorMessage = '';

        if (field.id === 'firstName') {
            if (field.value.trim() === '') {
                errorMessage = 'Das Feld Vorname darf nicht leer sein.';
                isValid = false;
            } else if (!/^[A-Za-zäöüÄÖÜß]+$/.test(field.value)) {
                errorMessage = 'Vorname darf nur Buchstaben enthalten.';
                isValid = false;
            }
        } else if (field.id === 'lastName') {
            if (field.value.trim() === '') {
                errorMessage = 'Das Feld Nachname darf nicht leer sein.';
                isValid = false;
            } else if (!/^[A-Za-zäöüÄÖÜß]+$/.test(field.value)) {
                errorMessage = 'Nachname darf nur Buchstaben enthalten.';
                isValid = false;
            }
        } else if (field.id === 'email') {
            if (field.value.trim() === '') {
                errorMessage = 'Das Feld E-Mail-Adresse darf nicht leer sein.';
                isValid = false;
            } else if (!/.+@.+\..+/.test(field.value.trim())) {
                errorMessage = 'Bitte geben Sie eine gültige E-Mail-Adresse ein.';
                isValid = false;
            }
        } else if (field.id === 'phone') {
            if (field.value.trim() === '') {
                errorMessage = 'Das Feld Telefonnummer darf nicht leer sein.';
                isValid = false;
            } else if (!/^[+\d]+$/.test(field.value)) {
                errorMessage = 'Bitte geben Sie eine gültige Telefonnummer ein.';
                isValid = false;
            }
        } else if (field.id === 'password') {
            if (field.value.trim() === '') {
                errorMessage = 'Das Feld Passwort darf nicht leer sein.';
                isValid = false;
            } else if (field.value.length < 8) {
                errorMessage = 'Passwort muss mindestens 8 Zeichen lang sein.';
                isValid = false;
            }
        }

        if (isValid) {
            clearFieldErrors(field);
        } else {
            field.classList.add('is-invalid');
            displayFieldErrors(field, errorMessage);
        }
    }

    // Function to validate password fields during password update
    function validatePasswordField(field) {
        var isValid = true;
        var errorMessage = '';

        if (field.id === 'current_password' || field.id === 'new_password') {
            if (field.value.trim() === '') {
                errorMessage = 'Das Passwort darf nicht leer sein.';
                isValid = false;
            } else if (field.value.length < 8) {
                errorMessage = 'Das Passwort muss mindestens 8 Zeichen lang sein.';
                isValid = false;
            }
        }

        if (isValid) {
            clearFieldErrors(field);
        } else {
            field.classList.add('is-invalid');
            displayFieldErrors(field, errorMessage);
        }
    }

    // Validation on field blur
    var inputs = document.querySelectorAll('input');
    inputs.forEach(function (input) {
        input.addEventListener('blur', function () {
            if (input.form.id === 'loginForm') {
                validateLoginField(input);
            } else if (input.form.id === 'registerForm') {
                validateRegisterField(input);
            } else if (input.form.id === 'updatePasswordForm') {
                validatePasswordField(input);
            }
        });
    });

    // Form validation on submit
    function validateForm(formId) {
        var isValid = true;
        var fields = document.querySelectorAll(`#${formId} input`);
        fields.forEach(function (input) {
            if (formId === 'loginForm') {
                validateLoginField(input);
            } else if (formId === 'registerForm') {
                validateRegisterField(input);
            } else if (formId === 'updatePasswordForm') {
                validatePasswordField(input);
            }
            if (input.classList.contains('is-invalid')) {
                isValid = false;
            }
        });

        if (formId === 'loginForm') {
            var errorContainer = document.querySelector('#loginForm .error-messages');
            if (!document.querySelector('#loginForm .is-invalid')) {
                if (errorContainer) {
                    errorContainer.textContent = ''; // Clear previous error messages
                }
            } else {
                displayGeneralErrors('Bitte überprüfen Sie die eingegebenen Daten.');
            }
        } else if (formId === 'updatePasswordForm') {
            var formFeedback = document.getElementById('formFeedback');
            if (!document.querySelector('#updatePasswordForm .is-invalid')) {
                if (formFeedback) {
                    formFeedback.style.display = 'none'; // Clear previous error messages
                }
            } else {
                displayGeneralErrors('Bitte beheben Sie die Fehler im Formular.');
            }
        }

        return isValid;
    }

    // Login form
    var loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (event) {
            if (!validateForm('loginForm')) {
                event.preventDefault(); 
            }
        });
    }

    // Registration form
    var registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (event) {
            if (!validateForm('registerForm')) {
                event.preventDefault(); 
            }
        });
    }
    
    // Password update form
    var updatePasswordForm = document.getElementById('updatePasswordForm');
    if (updatePasswordForm) {
        updatePasswordForm.addEventListener('submit', function (event) {
            if (!validateForm('updatePasswordForm')) {
                event.preventDefault(); 
            }
        });
    }
});