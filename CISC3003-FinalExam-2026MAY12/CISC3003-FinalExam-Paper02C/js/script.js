const registerForm = document.querySelector("#register-form");
const loginForm = document.querySelector("#login-form");
const studentIdField = document.querySelector("#student_id");
const emailField = document.querySelector("#email");
const passwordField = document.querySelector("#password");
const confirmPasswordField = document.querySelector("#confirm_password");
const studentIdHint = document.querySelector("#student-id-hint");
const emailAvailability = document.querySelector("#email-availability");
const passwordHint = document.querySelector("#password-hint");
const confirmPasswordHint = document.querySelector("#confirm-password-hint");
const registerButton = document.querySelector("#register-button");
const loginButton = document.querySelector("#login-button");

function setHint(element, message, isError) {
    if (!element) {
        return;
    }

    element.textContent = message;
    element.className = isError ? "error-text" : "supporting-text";
}

function validateStudentId() {
    if (!studentIdField) {
        return true;
    }

    const studentIdOk = /^[A-Z]{2}\d{6}$/.test(studentIdField.value.trim().toUpperCase());

    if (!studentIdOk) {
        studentIdField.setCustomValidity("Student ID must match a format like DC228400.");
        setHint(studentIdHint, "Student ID must match a format like DC228400.", true);
        return false;
    }

    studentIdField.setCustomValidity("");
    setHint(studentIdHint, "Use the format DC228400.", false);
    return true;
}

function validatePassword() {
    if (!passwordField) {
        return true;
    }

    const passwordOk = /^(?=.*[A-Z])(?=.*\d).{8,}$/.test(passwordField.value);

    if (!passwordOk) {
        passwordField.setCustomValidity("Password must be at least 8 characters and include one uppercase letter and one number.");
        setHint(passwordHint, "Password must be at least 8 characters and include one uppercase letter and one number.", true);
        return false;
    }

    passwordField.setCustomValidity("");
    setHint(passwordHint, "Use at least 8 characters, including one uppercase letter and one number.", false);
    return true;
}

function validateConfirmPassword() {
    if (!confirmPasswordField || !passwordField) {
        return true;
    }

    const passwordsMatch = passwordField.value !== "" && passwordField.value === confirmPasswordField.value;

    if (!passwordsMatch) {
        confirmPasswordField.setCustomValidity("Passwords do not match.");
        setHint(confirmPasswordHint, "Passwords do not match.", true);
        return false;
    }

    confirmPasswordField.setCustomValidity("");
    setHint(confirmPasswordHint, "Re-enter the same password exactly.", false);
    return true;
}

async function checkEmailAvailability(email) {
    if (!emailField || !emailAvailability || email.trim() === "") {
        return;
    }

    try {
        emailAvailability.textContent = "Checking email availability...";
        const response = await fetch(`php/validate_email.php?email=${encodeURIComponent(email)}`);
        const payload = await response.json();

        if (!payload.valid) {
            emailAvailability.textContent = "Please enter a valid email address first.";
            emailAvailability.className = "error-text";
            return;
        }

        if (payload.available) {
            emailAvailability.textContent = "This email address is available.";
            emailAvailability.className = "supporting-text";
        } else {
            emailAvailability.textContent = "This email address is already registered.";
            emailAvailability.className = "error-text";
        }
    } catch (error) {
        emailAvailability.textContent = "Unable to check email availability right now.";
        emailAvailability.className = "error-text";
    }
}

function validateRegisterForm() {
    if (!registerForm || !studentIdField || !emailField || !passwordField || !confirmPasswordField) {
        return true;
    }

    const emailOk = emailField.checkValidity();
    const studentIdOk = validateStudentId();
    const passwordOk = validatePassword();
    const passwordsMatch = validateConfirmPassword();
    registerForm.reportValidity();
    return emailOk && studentIdOk && passwordOk && passwordsMatch;
}

if (emailField) {
    emailField.addEventListener("input", () => {
        if (emailField.value.trim() === "") {
            setHint(emailAvailability, "Enter your email to check whether it is available.", false);
        }
    });

    emailField.addEventListener("blur", () => {
        checkEmailAvailability(emailField.value);
    });
}

if (studentIdField) {
    studentIdField.addEventListener("input", validateStudentId);
    studentIdField.addEventListener("blur", validateStudentId);
}

if (passwordField) {
    passwordField.addEventListener("input", () => {
        validatePassword();
        validateConfirmPassword();
    });
    passwordField.addEventListener("blur", validatePassword);
}

if (confirmPasswordField) {
    confirmPasswordField.addEventListener("input", validateConfirmPassword);
    confirmPasswordField.addEventListener("blur", validateConfirmPassword);
}

if (registerForm) {
    registerForm.addEventListener("submit", (event) => {
        if (!validateRegisterForm()) {
            event.preventDefault();
            return;
        }

        if (registerButton) {
            registerButton.textContent = "Creating Account...";
        }
    });
}

if (loginForm) {
    loginForm.addEventListener("submit", (event) => {
        if (!loginForm.reportValidity()) {
            event.preventDefault();
            return;
        }

        if (loginButton) {
            loginButton.textContent = "Signing In...";
        }
    });
}
