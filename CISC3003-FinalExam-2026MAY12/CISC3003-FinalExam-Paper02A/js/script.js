const experienceField = document.querySelector("#experience");
const counter = document.querySelector("#textarea-counter");
const submitButton = document.querySelector("#submit-button");
const resetButton = document.querySelector("#reset-button");

function updateCounter() {
    if (!experienceField || !counter) {
        return;
    }

    counter.textContent = `${experienceField.value.length} / 800 characters`;
}

if (experienceField) {
    updateCounter();
    experienceField.addEventListener("input", updateCounter);
}

if (submitButton) {
    submitButton.addEventListener("click", () => {
        submitButton.textContent = "Submitting...";
    });
}

if (resetButton) {
    resetButton.addEventListener("click", () => {
        window.requestAnimationFrame(updateCounter);
        if (submitButton) {
            submitButton.textContent = "Submit Form";
        }
    });
}
