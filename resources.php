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
</script>