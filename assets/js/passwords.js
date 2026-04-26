
function togglePasswords(classNames) {
    const password = document.querySelectorAll(`.${classNames}`);

    password.forEach((field) => {
        field.type = field.type === "password" ? "text" : "password";
    });
}
