<?
require_once __DIR__ . "/../../config/init.php";

$section = "calendario";
$title = "Calendario | " . APP_NAME;

ob_start();
?>


<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body p-0">

                    <div class="contentCalendar p-3">
                        <div id="calendar"></div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>


<!-- MODAL | New Event -->
<div class="modal fade" id="modalNewEvent" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <!-- <div class="modal-header">
                <h4 class="modal-title"><i class="bi bi-plus me-1"></i>Nuevo evento</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> -->
            <div class="modal-body">
                <form method="post" id="modalNewEvent_form" class="row">

                    <div class="col-12 mb-4 mt-2">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="modalNewEvent_titulo" name="titulo" placeholder="Título del evento">
                            <label for="titulo">Título del evento</label>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for=""><i class="bi bi-calendar-range me-1"></i>Fechas</label>
                        <div>
                            <input type="text" name="rangoEvento" id="modalNewEvent_rangoEvento" class="form-control" placeholder="Seleccione el rango..." />
                        </div>
                        <small class="text-secondary">Si lo desea puede seleccionar un rango</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-clock me-1"></i>Horario</label>
                        <div class="input-group">
                            <span class="input-group-text">Desde</span>
                            <input type="time" class="form-control" name="horaDesde" id="modalNewEvent_horaDesde">

                            <span class="input-group-text">hasta</span>
                            <input type="time" class="form-control" name="horaHasta" id="modalNewEvent_horaHasta">
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label"><i class="bi bi-chat-left-text me-1"></i>Descripción del evento</label>
                        <div id="modalNewEvent_descripcion" style="height: 80px;"></div>
                    </div>

                    <input type="hidden" name="action" value="calendario_create">
                    <input type="hidden" name="table" value="eventos">
                    <input type="hidden" name="pk" value="idEvento">
                    <input type="hidden" name="idUsuario" value="<?=$_SESSION["user"]["idUsuario"]?>">

                    <div class="col-12">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fa fa-times-circle me-1"></i>Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="modalNewEvent_handlerSubmit(this)"><i class="fa fa-calendar-check me-1"></i>Crear evento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL | Detail Event -->
<div class="modal fade" id="modalDetailEvent" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="bi bi-calendar-event me-1"></i><span id="modalDetailEvent_title">Nuevo evento</span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row mb-4">
                    <div class="col-md-4 d-flex justify-content-center align-items-center flex-column">
                        <p class="mb-0 fs-4">
                            <span id="modalDetailEvent_fechaInicio">--/--/----</span>
                            <span class="fs-6 ms-1" id="modalDetailEvent_horaInicio">--:--hs</span>
                        </p>
                        <p class="m-0 fs-6"><i class="bi bi-calendar-event me-1"></i>Inicio</p>
                    </div>
                    <div class="col-md-4 d-flex justify-content-center align-items-center flex-column">
                        <p class="mb-0 fs-4">
                            <span id="modalDetailEvent_fechaFin">--/--/----</span>
                            <span class="fs-6 ms-1" id="modalDetailEvent_horaFin">--:--hs</span>
                        </p>
                        <p class="m-0 fs-6"><i class="bi bi-calendar-event me-1"></i>Fin</p>
                    </div>
                    <div class="col-md-4 d-flex justify-content-center align-items-center flex-column">
                        <p class="mb-0 fs-4" id="modalDetailEvent_duracion">-</p>
                        <p class="m-0 fs-6"><i class="bi bi-clock me-1"></i>Duración</p>
                    </div>
                </div>
                
                <form method="post" id="modalDetailEvent_form">
                    <div id="modalDetailEvent_contentDescription"></div>

                    <input type="hidden" name="idEvento" value="" id="modalDetailEvent_idEvent">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="table" value="eventos">
                    <input type="hidden" name="pk" value="idEvento">

                    <input type="hidden" name="response_title" value="Descripción guardada!">
                    <input type="hidden" name="response_message" value="">

                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-primary" onclick="modalDetailEvent_handlerSubmit(this)"><i class="bi bi-save me-1"></i>Guardar</button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="modalDetailEvent_handlerDeleteEvent(this)"><i class="bi bi-trash me-1"></i>Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let calendar = null
    let modalNewEvent = null
    let modalNewEventDatePicker = null
    let modalNewEventTextArea = null
    
    let modalDetailEvent = null
    let modalDetailEventTextArea = null

    document.addEventListener("DOMContentLoaded", e => {
        initCalendar()

        initModalNewEvent()
        initModalDetailEvent()
    })

    function initCalendar() {
        const elCalendar = document.getElementById("calendar")

        // Inicializo el calendario
        calendar = new FullCalendar.Calendar(elCalendar, {
            events: '<?=DOMAIN_ADMIN?>process.php?action=calendar_events', 

            timeZone: 'America/Argentina/Buenos_Aires', 
            locale: 'es',
            themeSystem: 'bootstrap5',
            height: 800,

            headerToolbar: {
                start: 'today prev,next',
                center: 'title',
                end: 'multiMonthYear,dayGridMonth,timeGridWeek'
            },
            initialView: 'dayGridMonth',

            slotDuration: '00:30:00',
            slotLabelFormat: {
                hour: 'numeric',
                minute: '2-digit',
                omitZeroMinute: false,
            },
            slotMinTime: '07:00:00',
            slotMaxTime: '22:00:00',
            allDaySlot: false,

            dateClick: function(info) {
                const currentView = info.view.type
                const daySelected = info.dateStr // Y-m-d
                const coordinatesXY = info.jsEvent.pageX + ',' + info.jsEvent.pageY
                /* console.log("------- dateClick -------")
                console.log(currentView, daySelected, coordinatesXY) */

                // Redirecciono a la fecha seleccionada
                if (!["timeGridWeek", "timeGridDay"].includes(currentView)) {
                    calendar.changeView('timeGridDay', daySelected);
                    return
                }

                // Pido confirmación antes de crear el nuevo evento
                openModalNewEvent(daySelected.substr(0,19)) // Para no enviar el UTC
            },

            /* Redirecciona a la fecha seleccionada */
            navLinks: true,
            navLinkDayClick: function(date, jsEvent) {
                /* console.log('day', date.toISOString());
                console.log('coords', jsEvent.pageX, jsEvent.pageY); */

                const dateSelected = date.toISOString().split("T")[0]
                calendar.changeView('timeGridDay', dateSelected);
            },

            eventClick: function(info) {
                /* console.log('Event: ' + info.event.title);
                console.log('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
                console.log('View: ' + info.view.type); */

                /* console.log("------- eventClick -------")
                console.log(info.event.title, info.view.type, coordinatesXY) */

                // change the border color just for fun
                // info.el.style.borderColor = 'red';

                // Abrir modal con detalle
                openModalDetailEvent(info.event.extendedProps)

            },

            editable: true,
            eventChange: function(changeInfo) {

                Swal.fire({
                    title: "¿Está seguro que desea modificar el horario?",
                    text: "",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si",
                    cancelButtonText: "No"
                }).then((result) => {
                    if (!result.isConfirmed) {
                        changeInfo.revert();
                        return
                    }

                    let formData = new FormData()
                    formData.append("action", "save")
                    formData.append("table", "eventos")
                    formData.append("pk", "idEvento")
                    formData.append("idEvento", changeInfo.event.id)
                    formData.append("fechaInicio", changeInfo.event.start.toISOString())
                    formData.append("fechaFin", changeInfo.event.end.toISOString())
                    formData.append("response_title", "Modificado!")
                    
                    fetch(
                        "<?= DOMAIN_ADMIN ?>process.php", 
                        {
                            method: "POST", 
                            body: formData
                        }
                    )
                    .then(res => res.json())
                    .then(({status, title, message, type}) => {
                        Swal.fire(title, message, type).then(res => {
                            if(status != "OK") changeInfo.revert()
                        })
                    })
                });
                
            }
        });
        calendar.render();
    }

    // Modal | New Event
    function initModalNewEvent(){   

        // Modal init
        modalNewEvent = new bootstrap.Modal(document.getElementById('modalNewEvent'), {keyboard: false})

        // Inicializo el datepicker
        modalNewEventDatePicker = new Datepicker('#modalNewEvent_rangoEvento', {
            ranged: true,
            /* min: "<?= date("Y-m-d") ?>" */
        });

        // Modal new Event
        modalNewEventTextArea = new TextareaEditor("#modalNewEvent_descripcion")
        modalNewEventTextArea.initBasic()
    }
    function openModalNewEvent(datetimeSelected){
        const [date, hour] = datetimeSelected.split("T")

        // Seteo el titulo
        document.getElementById("modalNewEvent_titulo").value = ""

        // Seteo el datepicker
        const objDate = new Date(datetimeSelected)
        modalNewEventDatePicker.setDate([objDate])

        // Actualizo el valor de la hora de inicio y fin
        document.getElementById("modalNewEvent_horaDesde").value = hour.substr(0,5)
        document.getElementById("modalNewEvent_horaHasta").value = ""

        // Seteo la descripcion
        modalNewEventTextArea.setContent("")

        // Muestro el modal
        modalNewEvent.show()
    }
    function modalNewEvent_handlerSubmit(elemBtnSubmit){
        Swal.fire({
            title: "¿Está seguro?",
            text: "",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (!result.isConfirmed) return
            
            let btnSubmit = new FormButtonSubmitController(elemBtnSubmit, false)
            btnSubmit.init()
            
            let formData = new FormData(document.getElementById("modalNewEvent_form"))
            formData.append("descripcion", modalNewEventTextArea.getHTML())

            fetch(
                "<?= DOMAIN_ADMIN ?>process.php", 
                {
                    method: "POST", 
                    body: formData
                }
            )
            .then(res => res.json())
            .then(({status, title, message, type}) => {
                btnSubmit.reset()

                Swal.fire(title, message, type).then(res => {
                    if(status == "OK"){
                        // Refresh calendar
                        calendar.refetchEvents()
                        
                        // Oculto el modal
                        modalNewEvent.hide()
                    }
                })
            })
        });
    }

    // Modal | Show Event
    function initModalDetailEvent(){   

        // Modal init
        modalDetailEvent = new bootstrap.Modal(document.getElementById('modalDetailEvent'), {keyboard: false})

    }
    function openModalDetailEvent(event){

        console.log(event)

        // Título
        document.getElementById("modalDetailEvent_title").innerHTML = event.titulo
        
        // Descripcion
        for (const elContentDescripcion of document.querySelectorAll("#modalDetailEvent_contentDescription > div")) elContentDescripcion.remove()
        document.getElementById("modalDetailEvent_contentDescription").innerHTML = `<div id="modalDetailEvent_description"></div>`
        document.getElementById("modalDetailEvent_description").innerHTML = event.descripcion

        // Fecha de inicio
        document.getElementById("modalDetailEvent_fechaInicio").innerHTML = event.fechasFormateadas.desde.fecha
        document.getElementById("modalDetailEvent_horaInicio").innerHTML = event.fechasFormateadas.desde.hora + "hs"
        
        // Fecha de fin
        document.getElementById("modalDetailEvent_fechaFin").innerHTML = event.fechasFormateadas.hasta.fecha
        document.getElementById("modalDetailEvent_horaFin").innerHTML = event.fechasFormateadas.hasta.hora + "hs"
        
        // Duracion
        document.getElementById("modalDetailEvent_duracion").innerHTML = event.duracion.str

        // idEvento
        document.getElementById("modalDetailEvent_idEvent").value = event.idEvento

        // Muestro el contenido de la descripción
        modalDetailEventTextArea = new TextareaEditor("#modalDetailEvent_description")
        modalDetailEventTextArea.initBasic()

        // Muestro el modal
        modalDetailEvent.show()
    }
    function modalDetailEvent_handlerSubmit(elemBtnSubmit){
        let btnSubmit = new FormButtonSubmitController(elemBtnSubmit, false)
        btnSubmit.init()

        let formData = new FormData(document.getElementById("modalDetailEvent_form"))
        formData.append("descripcion", modalDetailEventTextArea.getHTML())

        fetch(
            "<?= DOMAIN_ADMIN ?>process.php", 
            {
                method: "POST", 
                body: formData
            }
        )
        .then(res => res.json())
        .then(({status, title, message, type}) => {
            btnSubmit.reset()
            Swal.fire(title, message, type).then()
        })
    }
    /* TODO: Terminar funcion */
    function modalDetailEvent_handlerDeleteEvent(elemBtnDelete){
        const idEvent = document.getElementById("modalDetailEvent_idEvent").value
        const eventObj = calendar.getEventById(idEvent)

        // Pido confirmación

        // Eliminod evento
        // event.remove()
    }
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
