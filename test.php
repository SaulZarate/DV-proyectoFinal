<? require_once __DIR__ . "/config/init.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    
  <!-- Favicons -->
  <link href="<?=DOMAIN_NAME?>assets/img/favicon.png" rel="icon">
  <link href="<?=DOMAIN_NAME?>assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?=DOMAIN_NAME?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?=DOMAIN_NAME?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?=DOMAIN_NAME?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?=DOMAIN_NAME?>assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="<?=DOMAIN_NAME?>assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="<?=DOMAIN_NAME?>assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?=DOMAIN_NAME?>assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Datapicker -->
  <link rel="stylesheet" href="<?=DOMAIN_NAME?>assets/vendor/datepicker/datepicker.material.css">

  <!-- Unpkg | Upload file with preview -->
  <link rel="stylesheet" href="<?=DOMAIN_NAME?>/assets/vendor/unpkg/main.css">
  

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
  
  <!-- Template Main CSS File -->
  <link href="<?=DOMAIN_NAME?>assets/css/style.css?v=<?=date("Ymd_His", filemtime(PATH_SERVER.'assets/css/style.css'))?>" rel="stylesheet">

</head>

<body>

    <select class="js-choice" data-type="select-one"></select>

    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script>
        /* ----------------------------- */
        /*                               */
        /*        EXAMPLE FETCH API      */
        /*                               */
        /* ----------------------------- */

        const element = document.querySelector('.js-choice');
        const choices = new Choices(element, {
            placeholderValue: "Clientes",
            loadingText: "Buscando clientes...",
            itemSelectText: 'Presione para seleccionar',
            noResultsText: 'Sin resultados',
        })
        choices.setChoices(() => {
            return fetch('./test.back.php?time=<?= time() ?>').then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    /* console.log(data) */

                    return data.results

                    /* return data.results.map(result => {
                        return {
                            label: result.label,
                            value: result.value,
                            customProperties: result.customProperties
                        };
                    }); */
                });
        })
        element.addEventListener("choice", e => {
            console.info("Option selected:",e.detail)
        })

        /*                               */
        /*          EXAMPLE SIMPLE       */
        /*                               */
        /* const data = [{
                value: 'Pepito 1',
                label: 'Pepito 1',
            },
            {
                value: 'Pepito 2',
                label: 'Pepito 2',
                customProperties: {
                    description: 'Custom description about Pepito 2',
                    random: 'Another random custom property'
                },
            },
            {
                label: 'Group 1',
                choices: [{
                        value: 'Pepito 3',
                        label: 'Pepito 3',
                    },
                    {
                        value: 'Pepito 4',
                        label: 'Pepito 4',
                    }
                ]
            }
        ]

        const element = document.querySelector('.js-choice');
        const choices = new Choices(element, {
            choices: data,
            placeholderValue: "Clientes",
            loadingText: "Buscando clientes...",
            itemSelectText: 'Presione para seleccionar',
            noResultsText: 'Sin resultados',
        });

        element.addEventListener("search", data => {
            console.log(data)
        }) */
    </script>
</body>

</html>