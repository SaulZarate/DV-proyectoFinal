/**
* Template Name: NiceAdmin
* Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
* Updated: Apr 20 2024 with Bootstrap v5.3.3
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

const DOMAIN_NAME = "http://localhost/proyectoFinal/";
const APP_NAME = "TurApp";

(function() {
  "use strict";

  /**
   * Easy selector helper function
   */
  const select = (el, all = false) => {
    el = el.trim()
    if (all) {
      return [...document.querySelectorAll(el)]
    } else {
      return document.querySelector(el)
    }
  }

  /**
   * Easy event listener function
   */
  const on = (type, el, listener, all = false) => {
    if (all) {
      select(el, all).forEach(e => e.addEventListener(type, listener))
    } else {
      select(el, all).addEventListener(type, listener)
    }
  }

  /**
   * Easy on scroll event listener 
   */
  const onscroll = (el, listener) => {
    el.addEventListener('scroll', listener)
  }

  /**
   * Sidebar toggle
   */
  if (select('.toggle-sidebar-btn')) {
    on('click', '.toggle-sidebar-btn', function(e) {
      select('body').classList.toggle('toggle-sidebar')
    })
  }

  /**
   * Search bar toggle
   */
  if (select('.search-bar-toggle')) {
    on('click', '.search-bar-toggle', function(e) {
      select('.search-bar').classList.toggle('search-bar-show')
    })
  }

  /**
   * Navbar links active state on scroll
   */
  let navbarlinks = select('#navbar .scrollto', true)
  const navbarlinksActive = () => {
    let position = window.scrollY + 200
    navbarlinks.forEach(navbarlink => {
      if (!navbarlink.hash) return
      let section = select(navbarlink.hash)
      if (!section) return
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        navbarlink.classList.add('active')
      } else {
        navbarlink.classList.remove('active')
      }
    })
  }
  window.addEventListener('load', navbarlinksActive)
  onscroll(document, navbarlinksActive)

  /**
   * Toggle .header-scrolled class to #header when page is scrolled
   */
  let selectHeader = select('#header')
  if (selectHeader) {
    const headerScrolled = () => {
      if (window.scrollY > 100) {
        selectHeader.classList.add('header-scrolled')
      } else {
        selectHeader.classList.remove('header-scrolled')
      }
    }
    window.addEventListener('load', headerScrolled)
    onscroll(document, headerScrolled)
  }

  /**
   * Back to top button
   */
  let backtotop = select('.back-to-top')
  if (backtotop) {
    const toggleBacktotop = () => {
      if (window.scrollY > 100) {
        backtotop.classList.add('active')
      } else {
        backtotop.classList.remove('active')
      }
    }
    window.addEventListener('load', toggleBacktotop)
    onscroll(document, toggleBacktotop)
  }

  /**
   * Initiate tooltips
   */
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })

  /**
   * Initiate quill editors
   */
  if (select('.quill-editor-default')) {
    new Quill('.quill-editor-default', {
      theme: 'snow'
    });
  }

  if (select('.quill-editor-bubble')) {
    new Quill('.quill-editor-bubble', {
      theme: 'bubble'
    });
  }

  if (select('.quill-editor-full')) {
    new Quill(".quill-editor-full", {
      modules: {
        toolbar: [
          [{
            font: []
          }, {
            size: []
          }],
          ["bold", "italic", "underline", "strike"],
          [{
              color: []
            },
            {
              background: []
            }
          ],
          [{
              script: "super"
            },
            {
              script: "sub"
            }
          ],
          [{
              list: "ordered"
            },
            {
              list: "bullet"
            },
            {
              indent: "-1"
            },
            {
              indent: "+1"
            }
          ],
          ["direction", {
            align: []
          }],
          ["link", "image", "video"],
          ["clean"]
        ]
      },
      theme: "snow"
    });
  }

  /**
   * Initiate TinyMCE Editor
   */

  const useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const isSmallScreen = window.matchMedia('(max-width: 1023.5px)').matches;

  tinymce.init({
    selector: 'textarea.tinymce-editor',
    plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion',
    editimage_cors_hosts: ['picsum.photos'],
    menubar: 'file edit view insert format tools table help',
    toolbar: "undo redo | accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl",
    autosave_ask_before_unload: true,
    autosave_interval: '30s',
    autosave_prefix: '{path}{query}-{id}-',
    autosave_restore_when_empty: false,
    autosave_retention: '2m',
    image_advtab: true,
    link_list: [{
        title: 'My page 1',
        value: 'https://www.tiny.cloud'
      },
      {
        title: 'My page 2',
        value: 'http://www.moxiecode.com'
      }
    ],
    image_list: [{
        title: 'My page 1',
        value: 'https://www.tiny.cloud'
      },
      {
        title: 'My page 2',
        value: 'http://www.moxiecode.com'
      }
    ],
    image_class_list: [{
        title: 'None',
        value: ''
      },
      {
        title: 'Some class',
        value: 'class-name'
      }
    ],
    importcss_append: true,
    file_picker_callback: (callback, value, meta) => {
      /* Provide file and text for the link dialog */
      if (meta.filetype === 'file') {
        callback('https://www.google.com/logos/google.jpg', {
          text: 'My text'
        });
      }

      /* Provide image and alt text for the image dialog */
      if (meta.filetype === 'image') {
        callback('https://www.google.com/logos/google.jpg', {
          alt: 'My alt text'
        });
      }

      /* Provide alternative source and posted for the media dialog */
      if (meta.filetype === 'media') {
        callback('movie.mp4', {
          source2: 'alt.ogg',
          poster: 'https://www.google.com/logos/google.jpg'
        });
      }
    },
    height: 600,
    image_caption: true,
    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
    noneditable_class: 'mceNonEditable',
    toolbar_mode: 'sliding',
    contextmenu: 'link image table',
    skin: useDarkMode ? 'oxide-dark' : 'oxide',
    content_css: useDarkMode ? 'dark' : 'default',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
  });

  /**
   * Initiate Bootstrap validation check
   */
  var needsValidation = document.querySelectorAll('.needs-validation')

  Array.prototype.slice.call(needsValidation)
    .forEach(function(form) {
      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })

  /**
   * Initiate Datatables
   */
  const datatables = select('.datatable', true)
  datatables.forEach(datatable => {
    new simpleDatatables.DataTable(datatable, {
      labels: {
        placeholder: "Buscador...",
        searchTitle: "Search within table",
        pageTitle: "Page {page}",
        perPage: "filas por página",
        noRows: "Sin filas encontradas",
        /* info: "Mostrar {start} a {end} de {rows} filas", */
        info: "Resultados: {rows}",
        noResults: "No hay resultados",
      },
      perPageSelect: [5, 10, 25, 50, 100, ["Todos", -1]],
      fixedHeight: true
    })/* .on('datatable.search', function(query, matched) {
      console.log("event search...")
      console.log(query)
      console.log(matched)
    }) */
  })



  /**
   * Autoresize echart charts
   */
  const mainContainer = select('#main');
  if (mainContainer) {
    setTimeout(() => {
      new ResizeObserver(function() {
        select('.echart', true).forEach(getEchart => {
          echarts.getInstanceByDom(getEchart).resize();
        })
      }).observe(mainContainer);
    }, 200);
  }

})();




/* ----------------------- */
/*      MIS FUNCIONES      */
/* ----------------------- */

function printFormData(formData){
  for (var pair of formData.entries()) {
    console.log(pair[0]+ ': ' + pair[1]); 
  }
}

function handlerLogout(){
  let data = new FormData()
  data.append("action", "logout");
  
  fetch(
    DOMAIN_NAME + "admin/process.php", 
    {
        method: "POST",
        body: data,
    }
  )
  .then(res => res.json())
  .then(response => {
      if(response.status === "OK") location.href = response.redirection
  })
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




/* ------------------- */
/*      MIS CLASES     */
/* ------------------- */

class HTMLController{
  mainElement = null

  constructor(select = ""){
    if(select){
      this.mainElement = document.querySelector(select)
    }else{
      this.mainElement = document.querySelector("body") 
    }

  }

  addClass(select, list){
    for (let element of this.mainElement.querySelectorAll(select)) {
      for (const item of list.split(" ")) {
        element.classList.add(item)
      }
    }
  }
  
  removeClass(select, list){
    for (let element of this.mainElement.querySelectorAll(select)) {
      for (const item of list.split(" ")) {
        element.classList.remove(item)
      }
    }
  }

  /**
   * Agrega/modifica las propiedades de los elementos 
   * @param {string} select Selector de los elementos
   * @param {object} prop {nameProperty: value, ...}
   */
  updateProp(select, prop){
    for (let element of this.mainElement.querySelectorAll(select)) {
      for (const [key, value] of Object.entries(prop)) {
        element[key] = value
      }
    }
  }


  static setProp(select, prop){
    for (let element of document.querySelectorAll(select)) {
      for (const [key, value] of Object.entries(prop)) {
        element[key] = value
      }
    }
  }

  static selectElementVisible(select){
    let elements = []
    for (let element of document.querySelectorAll(select)) {
      /* if(element.style.display == "none") continue; */
      if(element.offsetParent === null) continue;

      // Elemento visible
      elements.push(element)
    }

    return elements
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

class HTTP{
  static redirect(url){
    location.href = url
  }

  static openWindow(url){
    window.open(url)
  }

  static backURL(){
    history.back()
  }
}

class FormController{

  // Validacion de inputs/selects de formularios
  static validateForm(elementForm, min=1){

    const tagNameElement = elementForm.tagName.toLowerCase()
    const typeElement = elementForm.type
    const valueElement = elementForm.value.trim()

    // Validaciones especiales para inputs
    if(["text", "email", "password", "tel"].includes(typeElement)){
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

  static initOnlyShow(selector){
    new Quill(selector, {
      modules: { toolbar: false },
      theme: 'snow'
    }).enable(false);
  }

  getHTML(){
    return this.textarea.getSemanticHTML()
  }

  /**
   * Inicializa un textarea editable básico que cuenta con las siguientes opciones:
   * - Tamaños de letras
   * - bold | italic | underline | strike | link
   * - color de texto | color de fondo
   * - listas ordenadas | listas desordenadas | alineación
   * - Limpiador de estilos
   * 
   * @param {*} option 
   * 
   * @return void
   */
  initBasic(option = null){
    if(!option) {
      option = {
        theme: 'snow',
        modules: {
          toolbar: [
            [
              {
                size: []
              }
            ],
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
    }

    this.textarea = new Quill(this.selector, option);
  }

}