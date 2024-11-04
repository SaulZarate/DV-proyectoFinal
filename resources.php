<script>
    Swal.fire({
        title: "¿Quieres crear un nuevo evento?",
        text: "",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "No"
    }).then((result) => {
        if (!result.isConfirmed) return
        
    });



    fetch(
            "<?= DOMAIN_ADMIN ?>process.php", {
                method: "POST",
                body: new FormData(document.querySelector("selector"))
            }
        )
        .then(res => res.json())
        .then(({
            status,
            title,
            message,
            type
        }) => {
            btnSubmit.reset()

            Swal.fire(title, message, type).then(res => {
                if (status == "OK") HTTP.redirect("<?= DOMAIN_ADMIN ?>")
            })
        })
</script>