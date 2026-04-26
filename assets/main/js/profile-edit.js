document.addEventListener("DOMContentLoaded", function() { 
    document.getElementById('upload-pic').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const picture = document.querySelectorAll('#profile-preview');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                picture.forEach((img) => {
                    img.src = e.target.result;
                })
            };
            reader.readAsDataURL(file);
        }
    });
})