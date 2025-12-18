document.getElementById('switch').addEventListener('click', (event) => {
    const email = document.getElementById("email");
    const id = document.getElementById("id");
    const recipient_field = document.getElementById("recipient_field");

    email.classList.toggle("selected");
    id.classList.toggle("selected");

    if (email.classList.contains('selected')) {
        recipient_field.setAttribute('placeholder', "Recipient email")
    } else {
        recipient_field.setAttribute('placeholder', "Recipient ID")
    }

    
});
const form = document.getElementById('form');
const email_RGX = /^[A-Za-z0-9]+@gmail.com$/;
const string_RGX = /^[A-Za-z0-9 .,&'()\-]+$/;

form.addEventListener('submit', (e) => {
    if (email.classList.contains('selected') && !email_RGX.test(recipient_field.value)) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Please enter a Valid email",
        });
        e.preventDefault();
        return
    }
    if (!form.querySelector('[name="date"]').value) {
        Swal.fire({icon: "error", title: "Oops...", text: "Please enter a valid date",});
        e.preventDefault()
        return
    }
    if (!string_RGX.test(form.querySelector('[name="desc"]').value)) {
        Swal.fire({icon: "error", title: "Oops...", text: "Please enter a valid description",});
        e.preventDefault()
        return
    }
});