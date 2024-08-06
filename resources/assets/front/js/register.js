function togglePasswordVisibility(fieldName) {
    var input = document.getElementsByName(fieldName)[0];
    var eyeIcon = input.nextElementSibling;

    if (input.type === "password") {
        input.type = "text";
        eyeIcon.className = "fa fa-fw fa-eye field-icon";
    } else {
        input.type = "password";
        eyeIcon.className = "fa fa-fw fa-eye-slash field-icon";
    }
}
// Document is ready
$(document).ready(function () {
    // Validate Password
    $("#passcheck").hide();
    let passwordError = true;
    $("#password").keyup(function () {
        validatePassword();
    });
    function validatePassword() {
        let passwordValue = $("#password").val();
        if (passwordValue.length == "") {
            $("#passcheck").show();
            $("#passcheck").html("Please enter a password");
            $("#passcheck").css("color", "red");
            passwordError = false;
            return false;
        }
        if (passwordValue.length < 8 ) {
            $("#passcheck").show();
            $("#passcheck").html("your password contains at least 8 characters.");
            $("#passcheck").css("color", "red");
            passwordError = false;
            return false;
        }

        // New password complexity checks
        let hasUppercase = /[A-Z]/.test(passwordValue);
        let hasLowercase = /[a-z]/.test(passwordValue);
        let hasNumbers = /[0-9]/.test(passwordValue);
        let hasSpecialChars = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/.test(passwordValue);

        if (!(hasUppercase && hasLowercase && hasNumbers && hasSpecialChars)) {
            $("#passcheck").show();
            $("#passcheck").html("Your password is weak.<br>1.your password contains both upper and lower case letters symbols like @, _, #, * and numbers.");
            $("#passcheck").css("color", "red");
            passwordError = false;
            return false;
        } else {
            $("#passcheck").hide();
            passwordError = true;
            return passwordError;
        }
    }

    // Validate Confirm Password
    $("#conpasscheck").hide();
    let confirmPasswordError = true;
    $("#conpassword").keyup(function () {
        validateConfirmPassword();
    });
    function validateConfirmPassword() {
        let confirmPasswordValue = $("#conpassword").val();
        let passwordValue = $("#password").val();
        if (passwordValue != confirmPasswordValue) {
            $("#conpasscheck").show();
            $("#conpasscheck").html("Passwords do not match. Please enter the same password in both fields");
            $("#conpasscheck").css("color", "red");
            confirmPasswordError = false;
            return false;
        } else {
            $("#conpasscheck").hide();
            confirmPasswordError = true;
            return confirmPasswordError;
        }
    }

    //Submit button
    $("#submitbtn").click(function () {
        var passwordError = validatePassword();
        var confirmPasswordError = validateConfirmPassword();
        var name = $("#name").val();
        var email = $("#email").val();
        var phone = $("#phone").val();
        var nameError = false;
        var emailError = false;
        var phoneError = false;
        var termsChecked = $("#get_updates").prop("checked");

        // Validate name
        if (name.trim() === "") {
            $("#nameError").text("Name is required");
            //nameError = true;
        } else {
            $("#nameError").text("");
        }

        // Validate email
        if (email.trim() === "") {
            $("#emailError").text("Email is required");
            //emailError = true;
        } else if (!isValidEmail(email)) {
            $("#emailError").text("Please enter a valid email address.");
            //emailError = true;
        } else {
            $("#emailError").text("");
        }
        const phoneRegex = /^\d{0,11}$/;

        // Validate phone
        if (phone.trim() === "") {
            $("#phoneError").text("Phone no. is required");
           // phoneError = true;
        } else if (!phone.match(phoneRegex)) {
            $("#phoneError").text("Phone no. can have a maximum of 11 digits");
           // phoneError = true;
        } else {
            $("#phoneError").text("");
        }

        // Validate terms and conditions checkbox
        if (!termsChecked) {
            $("#get_updatesError").text("You must accept the terms and conditions to proceed.");
        } else {
            $("#get_updatesError").text("");
        }

        if (passwordError && confirmPasswordError && !nameError && !emailError && !phoneError && termsChecked) {
            $('#submitbtn').attr('type', 'submit');
        } else {
            $('#submitbtn').attr('type', 'button');
        }
    });
    function isValidEmail(email) {
        // Regular expression for basic email validation
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }


    $( "input" ).on( "focus", function() {
        $( ".error" ).html( "" );
    });

    $("#phone").keydown(function(event) {
        let _text=$(this).val();
        
        if ( event.keyCode == 46 || event.keyCode == 8 ) {
            // Allow only backspace and delete
        } else {
           
            if (event.keyCode < 48 || event.keyCode > 57 ) {
                event.preventDefault(); 
                // Ensure that it is a text and stop the keypress
            }
            
            if(_text.length >= 11) {
                event.preventDefault(); 
            }
        } 
    });
});