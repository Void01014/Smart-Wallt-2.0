const form = document.getElementById('form');
const email_RGX = /^[A-Za-z0-9]+@gmail.com$/;
const id_RGX = /^\d+(\.\d{1,2})?$/;
const string_RGX = /^[A-Za-z0-9 .,&'()\-]+$/;
const amount_RGX = /^\d+(\.\d{1,2})?$/;

const email = document.getElementById("email");
const id = document.getElementById("id");
const recipient_field = document.getElementById("recipient_field");
const id_form = document.getElementById("id_form");
const amount = document.getElementById("amount");

document.getElementById('switch').addEventListener('click', (event) => {
    email.classList.toggle("selected");
    id.classList.toggle("selected");

    if (email.classList.contains('selected')) {
        recipient_field.setAttribute('placeholder', "Recipient email");
        id_form.value = 'email';
    } else {
        recipient_field.setAttribute('placeholder', "Recipient ID");
        id_form.value = 'id';
    }

    
});

form.addEventListener('submit', (e) => {
    if (form.querySelector('[name="card_id"]').value == 'default') {
        Swal.fire({icon: "error", title: "Oops...", text: "Please enter a valid card",});
        e.preventDefault()
        return
    }
    if (!amount_RGX.test(amount.value) || amount.value < 0) {
        Swal.fire({icon: "error", title: "Oops...", text: "Please enter a valid amount",});
        e.preventDefault()
        return
    }
    if (email.classList.contains('selected') && !email_RGX.test(recipient_field.value)) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Please enter a Valid email",
        });
        e.preventDefault();
        return
    }
    if (id.classList.contains('selected') && !id_RGX.test(recipient_field.value) || recipient_field.value < 0) {
        Swal.fire({icon: "error", title: "Oops...", text: "Please enter a valid id",});
        e.preventDefault()
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