<!-- Vendor JS Files -->
<script src="<?=DOMAIN_NAME?>assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="<?=DOMAIN_NAME?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?=DOMAIN_NAME?>assets/vendor/chart.js/chart.umd.js"></script>
<script src="<?=DOMAIN_NAME?>assets/vendor/echarts/echarts.min.js"></script>
<script src="<?=DOMAIN_NAME?>assets/vendor/quill/quill.js"></script>
<script src="<?=DOMAIN_NAME?>assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="<?=DOMAIN_NAME?>assets/vendor/tinymce/tinymce.min.js"></script>

<!-- Datepicker -->
<script src="<?=DOMAIN_NAME?>assets/vendor/datepicker/datepicker.js"></script>

<!-- Unpkg | Upload file with preview -->
<script type="module">
    import { FileUploadWithPreview } from '<?=DOMAIN_NAME?>/assets/vendor/unpkg/main.js';

    if(!!document.querySelector(".custom-file-container")){
        const fileUploadWithPreview = new FileUploadWithPreview('custom-file-container', {
            multiple: true,
            accept: "image/*, video/mp4",
            text: {
                label: "Seleccione las imagenes/videos que desee agregar"
            }
        });
        
        // Para ser utilizado en cualquier script
        window.fileUploadWithPreview = fileUploadWithPreview
    }
</script>

<!-- Sortable -->
<script src="<?=DOMAIN_NAME?>assets/vendor/sortable/main.js"></script>

<!-- FullCalendar -->
<script src='<?=DOMAIN_NAME?>assets/vendor/fullcalendar/index.global.min.js'></script>
<script src='<?=DOMAIN_NAME?>assets/vendor/fullcalendar/locale.es.js'></script>
 
<!-- Alert 2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/deecb3ce02.js" crossorigin="anonymous"></script>




<!-- ------------------- -->
<!--        MAIN JS      -->
<!-- ------------------- -->
<script src="<?=DOMAIN_NAME?>assets/js/main.js"></script>