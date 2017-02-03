<script>
    $(document).on('lity:close', function() {

loadDirecciones();
    });

    var direccionIndex=0;

    function addDireccion(direccion)
    {

        var clone= $(".clone").clone();
        $(".cloned").append("<div data-id="+direccionIndex+">"+clone.html()+"</div>");
        clone=$("[data-id="+direccionIndex+"]");

        var name=clone.attr("name");

        var calle= clone.find("[data-name='calle']");
        var numero= clone.find("[data-name='numero']");
        var piso= clone.find("[data-name='piso']");
        var depto= clone.find("[data-name='depto']");
        var orden= clone.find("[data-name='orden']");


        calle.attr("name","direcciones["+direccionIndex+"][direccionCalle]");

        numero.attr("name","direcciones["+direccionIndex+"][direccionNumero]");

        piso.attr("name","direcciones["+direccionIndex+"][direccionPiso]");

        depto.attr("name","direcciones["+direccionIndex+"][direccionDepto]");

        orden.attr("name","direcciones["+direccionIndex+"][orden]");


        if(direccion)
        {


            calle.val(direccion.calle);
         numero.val(direccion.numero);
         piso.val(direccion.piso);
         depto.val(direccion.depto);


        }



        direccionIndex++;


    }



    function loadDirecciones()
    {
        $.ajax(
            {
                method:"get",
                url:"direcciones-data.php?act=list",
                dataType:"json",
                success:function (res) {
                    console.log(res);
                },
                error:function (err) {
                    console.log(err);
                }
            }
        );
    }

    $(document).ready(function()
    {

    });
    $(document).on("submit",".add form",function (e) {


        $.ajax(
            {
                method:"post",
                url:"<?php echo $urlSave; ?>",
                data:$(this).serialize(),
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
    <h2>Nuevo cliente</h2>
    <form>

        <div>
            <label>Nombre
            </label>
            <input name="clienteNombre">
        </div>
        <div>
            <label>Apellido</label>
            <input name="clienteApellido">
        </div>
        <div>
            <label>Notas</label>
            <textarea name="clienteNotas"></textarea>
        </div>
        <div>
            <label>Direcciones</label>
           <div class="clone" style="display: none">
               <div>
                   <label>Calle</label>
                   <input data-name="calle" >
               </div>
               <div>
                   <label>Numero</label>
                   <input data-name="numero">
               </div>
               <div>
                   <label>Piso</label>
                   <input data-name="piso" >
               </div>
               <div>
                   <label>Depto</label>
                   <input data-name="depto" >
               </div>
               <input data-name="orden" value="0" hidden>

           </div>
            <div class="cloned">

            </div>

                <a onclick="addDireccion()">Agregar direccion</a>


        </div>
        <div>
            <button type="submit">Aceptar</button>
        </div>
    </form>
</section>