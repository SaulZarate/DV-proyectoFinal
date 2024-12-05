function validateForm(elementForm, min = 1) {

    const tagNameElement = elementForm.tagName.toLowerCase()
    const typeElement = elementForm.type
    const valueElement = elementForm.value.trim()

    // Validaciones especiales para inputs
    if (["text", "email", "password", "tel", "number"].includes(typeElement)) {
        if (typeElement === "email") {
            if (isEmailValid(valueElement)) elementForm.classList.remove("is-invalid")
            else elementForm.classList.add("is-invalid")
            return
        }

        if (typeElement === "password") {
            if (isPasswordValid(valueElement)) elementForm.classList.remove("is-invalid")
            else elementForm.classList.add("is-invalid")
            return
        }

        if (typeElement === "tel") {
            /* if (getOnlyInt(valueElement).length < min) elementForm.classList.add("is-invalid")
            else elementForm.classList.remove("is-invalid") */
            elementForm.value = getOnlyInt(valueElement)
            return
        }

        if (typeElement === "number") {
            /* if (getOnlyNumber(valueElement).length < min) elementForm.classList.add("is-invalid")
            else elementForm.classList.remove("is-invalid") */
            elementForm.value = getOnlyNumber(valueElement)
            return
        }
    }


    // Validacion de fechas
    if (typeElement === "date") {
        if (valueElement.length != 10) elementForm.classList.add("is-invalid")
        else elementForm.classList.remove("is-invalid")
        return
    }

    // Validacion de horas
    if (typeElement === "time") {
        if (valueElement.length != 5) elementForm.classList.add("is-invalid")
        else elementForm.classList.remove("is-invalid")
        return
    }

    // Validacion general
    if (valueElement.length < min) elementForm.classList.add("is-invalid")
    else elementForm.classList.remove("is-invalid")
}

function isEmailValid(email){
    return email.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
}

// Valida que tenga una minuscula, una mayuscula, un numero, un caracter especial y que tenga 8 caracteres
function isPasswordValid(pw) {
    return /[A-Z]/.test(pw) && /[a-z]/.test(pw) && /[0-9]/.test(pw) && /[^A-Za-z0-9]/.test(pw) && pw.length >= 8;
}

function getOnlyInt(str){
    return str.replace(/\D/g, "");
}

function getOnlyNumber(str){
    return str.replace(/[^0-9\.]+/g,"");
}