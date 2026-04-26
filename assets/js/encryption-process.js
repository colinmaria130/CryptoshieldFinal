document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('encryptionForm');
    const encryptBtn = document.getElementById('encryptBtn');
    const decryptBtn = document.getElementById('decryptBtn');
    const encryptionTypeSelect = document.getElementById('encryption_type');
    const resultLabel = document.getElementById("resultLabel");
    const resultText = document.getElementById("resultText");
    const copyButton = document.getElementById("copyButton");

    function sendRequest(action) {
        const formData = new FormData(form);
        formData.append("action", action);
        formData.append("encryption_type", encryptionTypeSelect.value);

        fetch('http://localhost/cryptoshield_final/api/encryption_process.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    resultLabel.textContent = data.label + ":";
                    copyButton.style.display = "block";
                    resultText.textContent = data.text;
                } else {
                    resultLabel.textContent = "Error!";
                    resultText.classList.add("text-danger");
                    resultText.textContent = "Something went wrong.";
                }
            })
            .catch(error => {
                resultLabel.textContent = "Request Failed!";
                resultText.classList.add("text-danger");
                resultText.textContent = "Could not process your request.";
            });
    }

    encryptBtn.addEventListener('click', () => {
        const type = encryptionTypeSelect.value;
        sendRequest(type + "_encrypt");
    });

    decryptBtn.addEventListener('click', () => {
        const type = encryptionTypeSelect.value;
        sendRequest(type + "_decrypt");
    });
});

