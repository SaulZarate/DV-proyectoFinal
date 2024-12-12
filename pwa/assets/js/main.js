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
}