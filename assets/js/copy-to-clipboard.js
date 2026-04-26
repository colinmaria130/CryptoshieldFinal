function copyToClipboard(textArea) {

    const copyText = document.getElementById(textArea);
    const copyStatus = document.getElementById('copyStatus');

    navigator.clipboard.writeText(copyText.innerText).then(() => {

        copyStatus.classList.add('text-success');
        copyStatus.textContent = "Text Copied!";

        setTimeout(() => {
            document.getElementById("copyStatus").textContent = "";
        }, 2000);

    })
    
    .catch(err => {
        copyStatus.classList.add('text-danger');
        copyStatus.textContent = `Error in copying text: ${err}`;
        console.error("Failed to copy: ", err);
    });
}