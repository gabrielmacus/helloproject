<script>

    $(document).on("submit",".add form",function (e) {

        var data= getFormData($(this));

        console.log(data);

        $.ajax(
            {
                method:"post",
                url:"<?php echo $urlSave; ?>",
                data:data,
                dataType:"json",
                success:function (res) {
                    console.log(res);
                },
                error:function (err) {
                    console.log(err);
                }
            }
        );
        e.preventDefault();
    });

</script>
<section class="add">
    <h2>Nuevo direccion</h2>
    <form>

        <div>
            <label>Calle</label>
            <input name="direccionCalle">
        </div>
        <div>
            <label>Numero</label>
            <input name="direccionNumero">
        </div>
        <div>
            <label>Piso</label>
            <input name="direccionPiso">
        </div>
        <div>
            <label>Depto</label>
            <input name="direccionDepto">
        </div>
        <div>
            <label>Notas</label>
            <textarea name="direccionNotas"></textarea>
        </div>
        <div>
            <button type="submit">Aceptar</button>
        </div>
    </form>
</section>