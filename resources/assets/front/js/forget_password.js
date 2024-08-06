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

        if (passwordError && confirmPasswordError) {
            $('#submitbtn').attr('type', 'submit');
        } else {
            $('#submitbtn').attr('type', 'button');
        }
    });
});