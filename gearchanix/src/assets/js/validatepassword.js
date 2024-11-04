function validatePassword() {
    const password = document.getElementById('password').value;

    // Regex for password validation: At least 8 characters, 1 uppercase, 1 special character
    const passwordPattern = /^(?=.*[A-Z])(?=.*[\W_]).{8,}$/;

    if (!passwordPattern.test(password)) {
        alert('Password must be at least 8 characters long and include at least one uppercase letter and one special character.');
        return false; // Prevent form submission
    }

    return true; // Allow form submission
}

function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePassword');

    // Toggle the input type between password and text
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text'; // Show the password
        toggleIcon.classList.remove('fa-eye-slash'); // Change icon to 'eye'
        toggleIcon.classList.add('fa-eye'); // Show the open eye icon
    } else {
        passwordInput.type = 'password'; // Hide the password
        toggleIcon.classList.remove('fa-eye'); // Change icon to 'eye-slash'
        toggleIcon.classList.add('fa-eye-slash'); // Show the closed eye icon
    }
}

$(document).ready(function() {
    // Bind the form submission event
    $('#registrationForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        // Validate password before submission
        if (!validatePassword()) {
            return; // Stop if password validation fails
        }

        e.preventDefault(); // Prevent default form submission

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

// Validation Box
var password = document.getElementById("password");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

// Validation on click
password.onfocus = function(){
    document.getElementById("validation_box").style.display = "block";
}

// hide validation box 
password.onblur = function() {
    document.getElementById("validation_box").style.display = "none";
}

// user's input
password.onkeyup = function(){
    // validation for lowercase
    var lowerCaseLetters = /[a-z]/g;

    if(password.value.match(lowerCaseLetters)){
        letter.classList.remove("invalid");
        letter.classList.add("valid");
    }
    else {
        letter.classList.remove("valid");
        letter.classList.add("invalid");
    }

    // validation for uppercase
    var upperCaseLetters = /[A-Z]/g;

    if(password.value.match(upperCaseLetters)){
        capital.classList.remove("invalid");
        capital.classList.add("valid");
    }
    else {
        capital.classList.remove("valid");
        capital.classList.add("invalid");
    }

    // validation for numbers
    var numbers = /[0-9]/g;

    if(password.value.match(numbers)){
        number.classList.remove("invalid");
        number.classList.add("valid");
    }
    else {
        number.classList.remove("valid");
        number.classList.add("invalid");
    }

    // validation for length

    if(password.value.length >= 8){
        length.classList.remove("invalid");
        length.classList.add("valid");
    }
    else {
        length.classList.remove("valid");
        length.classList.add("invalid");
    }

}
