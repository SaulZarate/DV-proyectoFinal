(function() {
    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
})()


function isLogged(){
    return localStorage.getItem("user") !== null
}

function logout(){
    localStorage.removeItem("user");
    location.href = "login.html"
}

function handlerLogout(){
    alert("cerrar sesión")
    console.log("Cerrar sesión")
}

function getVarGET(){
  const urlParams = new URLSearchParams(window.location.search);
  
  let params = {}
  for (const param of urlParams.entries()) {
    params[param[0]] = param[1]
  }
  
  return params
}

function ucfirst(str){
  return String(str).charAt(0).toUpperCase() + String(str).slice(1);
}

function getCurrentDate(){
  var currentdate = new Date(); 
  const currentDate = currentdate.getDate() + "/" + (currentdate.getMonth()+1) + "/" + currentdate.getFullYear()/*  + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds(); */
    return currentDate
}

function dateFormat(strDate){
  const [anio, mes, dia] = strDate.split("-")
  return `${dia}/${mes}/${anio}`
}

function dateToAge(strDate){
    const getAge = birthDate => Math.floor((new Date() - new Date(birthDate).getTime()) / 3.15576e+10)
    return getAge(strDate)
}

/* ----------------- */
/*      PERFIL       */
/* ----------------- */

// Valida que tenga una minuscula, una mayuscula, un numero, un caracter especial y que tenga 8 caracteres
function isPasswordValid(pw) {
    return /[A-Z]/.test(pw) && /[a-z]/.test(pw) && /[0-9]/.test(pw) && /[^A-Za-z0-9]/.test(pw) && pw.length >= 8;
}

function decodeHtmlEntities(str) { 
    const textarea = document.createElement('textarea'); 
    textarea.innerHTML = str; 
    return textarea.value; 
} 


/* --------------- */
/*      CLASES     */
/* --------------- */
class TextareaEditor{
    selector = ""
    textarea = null
  
    constructor(selector = ""){
      if(selector) this.setElement(selector)
    }
  
    setElement(selector){
      this.selector = selector
      this.textarea = document.querySelector(selector)
    }
  
    getHTML(){
      return this.textarea.getSemanticHTML()
    }
  
    initBasicText(){
      const option = {
        theme: 'snow',
        modules: {
          toolbar: [
            ["bold", "italic", "underline", "strike", "link"],
            [
              {
                color: []
              }, 
              {
                background: []
              }
            ],
            [
              {
                list: "ordered"
              },
              {
                list: "bullet"
              },
              {
                align: []
              }
            ],
            ["clean"]
          ]
        }
      }
  
      this.textarea = new Quill(this.selector, option);
    }
    
    setContent(content = ""){
      this.textarea.setText(content)
      this.textarea.update();
    }
}

class FormController{

  // Validacion de inputs/selects de formularios
  static validateForm(elementForm, min=1){

    const tagNameElement = elementForm.tagName.toLowerCase()
    const typeElement = elementForm.type
    const valueElement = elementForm.value.trim()

    // Validaciones especiales para inputs
    if(["text", "email", "password", "tel", "number"].includes(typeElement)){
      if(typeElement === "email"){
        if(isEmailValid(valueElement)) elementForm.classList.remove("is-invalid")
          else elementForm.classList.add("is-invalid")
        return
      }
      
      if(typeElement === "password"){
        if(isPasswordValid(valueElement)) elementForm.classList.remove("is-invalid")
        else elementForm.classList.add("is-invalid")
        return
      }

      if(typeElement === "tel"){
        if(getOnlyInt(valueElement).length < min) elementForm.classList.add("is-invalid")
        else elementForm.classList.remove("is-invalid")
        elementForm.value = getOnlyInt(valueElement)  
        return
      }

      if(typeElement === "number"){
        if(getOnlyNumber(valueElement).length < min) elementForm.classList.add("is-invalid")
        else elementForm.classList.remove("is-invalid")
        elementForm.value = getOnlyNumber(valueElement)  
        return
      }
    }


    // Validacion de fechas
    if(typeElement === "date"){
      if(valueElement.length != 10) elementForm.classList.add("is-invalid")
      else elementForm.classList.remove("is-invalid")
      return
    }

    // Validacion de horas
    if(typeElement === "time"){
      if(valueElement.length != 5) elementForm.classList.add("is-invalid")
      else elementForm.classList.remove("is-invalid")
      return
    }

    // Validacion general
    if(valueElement.length < min) elementForm.classList.add("is-invalid")
    else elementForm.classList.remove("is-invalid")
  }


  static trigger(select, eventName){
    // Crear un nuevo evento personalizado
    const event = new Event(eventName, {
      bubbles: true, // Si deseas que el evento burbujee hacia arriba
      cancelable: true // Si deseas que el evento pueda ser cancelado
    });

    // Despachar el evento en el elemento proporcionado
    for (const element of document.querySelectorAll(select)) {
      element.dispatchEvent(event);
    }
  }
}

class FormButtonSubmitController extends FormController{
  elBtnSubmit = null
  contentHTMLBtnSubmit = ""
  /* htmlLoadingBtnSubmit = `Subiendo...` */
  htmlLoadingBtnSubmit = `<i class="fas fa-spinner fa-pulse me-1"></i>Subiendo...`

  /**
   * 
   * @param {string|object} element Puede ser un selector o un elemento html
   * @param {bool} isSelector Indica si el paramatro element es un objeto
   */
  constructor(element, isSelector = true){
    const el = isSelector ? document.querySelector(element) : element

    super();

    this.elBtnSubmit = el
    this.contentHTMLBtnSubmit = el.innerHTML
  }

  init(htmlButton = ""){
    this.elBtnSubmit.innerHTML = htmlButton ? htmlButton : this.htmlLoadingBtnSubmit
    this.elBtnSubmit.disabled = true
  }

  reset(){
    this.elBtnSubmit.innerHTML = this.contentHTMLBtnSubmit
    this.elBtnSubmit.disabled = false
  }
}