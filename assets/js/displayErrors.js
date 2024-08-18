function displayErrors(errors) {
    var errorContainer = document.querySelector('#formErrorMessages');
    if (!errorContainer) return;

    errorContainer.innerHTML = ''; 
    for (var field in errors) {
        if (errors.hasOwnProperty(field)) {
            var message = errors[field];
            var errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger';
            errorDiv.textContent = message;
            errorContainer.appendChild(errorDiv);

            var fieldElement = document.getElementById(field);
            if (fieldElement) {
                fieldElement.classList.add('is-invalid');
                var feedback = document.getElementById(field + 'Feedback');
                if (feedback) {
                    feedback.textContent = message;
                }
            }
        }
    }
}