const form = document.querySelector("#contact-form");
const messageField = document.querySelector("#message");
const counter = document.querySelector("#message-counter");
const sendButton = document.querySelector("#send-button");
const clearButton = document.querySelector("#clear-button");

function updateCounter() {
    if (!messageField || !counter) {
        return;
    }

    counter.textContent = `${messageField.value.length} / 1200 characters`;
}

function validateClientSide() {
    if (!form) {
        return true;
    }

    return form.reportValidity();
}

if (messageField) {
    updateCounter();
    messageField.addEventListener("input", updateCounter);
}

if (form) {
    form.addEventListener("submit", (event) => {
        if (!validateClientSide()) {
            event.preventDefault();
            return;
        }

        if (sendButton) {
            sendButton.textContent = "Sending...";
        }
    });
}

if (clearButton) {
    clearButton.addEventListener("click", () => {
        window.requestAnimationFrame(updateCounter);
        if (sendButton) {
            sendButton.textContent = "Send Message";
        }
    });
}
