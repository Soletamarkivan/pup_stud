$(document).ready(function() {
    // Bind the form submission event
    $('#registrationForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        // Validate password before submission
        if (!validatePassword()) {
            return; // Stop if password validation fails
        }

        $.ajax({
            url: 'register.php', // URL of the PHP script
            type: 'POST',
            data: $(this).serialize(), // Serialize form data
            dataType: 'json', // Expect a JSON response
            success: function(response) {
                if (response.success) {
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                    // Redirect to login page after modal is closed
                    successModal._element.addEventListener('hidden.bs.modal', function () {
                        window.location.href = '/gearchanix/gearchanix-main/gearchanix/public/pages/login-reg/login.html'; // Redirect to login page
                    });
                } else {
                    alert('Error during registration: ' + (response.error || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Error during registration: ' + xhr.responseText);
            }
        });
    });
});
