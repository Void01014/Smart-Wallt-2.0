let form = document.querySelector("#form");
let card_name = document.querySelector("#card_name");
let company_name = document.querySelector("#company_name");
let balance = document.querySelector("#balance");


form.addEventListener('submit', (e) => {
    const string_RGX = /^[A-Za-z0-9 .,&()\-]+$/;
    const amount_RGX = /^\d+(\.\d{1,2})?$/;

    if (!string_RGX.test(card_name.value)) {
        Swal.fire({icon: "error", title: "Oops...", text: "Please enter a valid name for the card",});
        e.preventDefault()
        return
    }
    if (company_name.value == "default") {
        Swal.fire({icon: "error", title: "Oops...", text: "Please enter a valid option",});
        e.preventDefault()
        return
    }
    if (!amount_RGX.test(balance.value) || Number(balance.value) < 0) {
        Swal.fire({icon: "error", title: "Oops...", text: "Please enter a valid amount",});
        e.preventDefault()
        return
    }
});
