<?php
/**
 * Created by PhpStorm.
 * User: Luis Garcia
 * Date: 16/01/2017
 * Time: 01:24 AM
 */

require("/includes/autoload.php");

function validateData($data)
{
    return true;
}
$result["error"]=false;
$result["success"]=false;
$act=$_GET["act"];
$id=$_GET["id"];

switch ($act)
{
    case 'list':

        $sql="SELECT c.*,d.* FROM clientes c  LEFT JOIN clientes_direcciones cd ON cd.cliente=c.clienteId LEFT JOIN direcciones d ON cd.direccion = d.direccionId";
        if($id)
        {
            $sql.=" WHERE c.clienteId={$id}";
        }

        $clientes=array();

        if($res=$db->query($sql))
        {
            $res=$res->fetch_all(1);

            foreach ($res as $cliente)
            {
                $clienteId=$cliente["clienteId"];

                $clientes[$clienteId]["clienteNombre"]=$cliente["clienteNombre"];
                $clientes[$clienteId]["clienteApellido"]=$cliente["clienteApellido"];
                $clientes[$clienteId]["clienteNotas"]=$cliente["clienteNotas"];
                $clientes[$clienteId]["clienteCreacion"]=$cliente["clienteCreacion"];
                $clientes[$clienteId]["clienteModificacion"]=$cliente["clienteModificacion"];

                if($cliente["direccionId"])
                {
                    $direccion["id"]=$cliente["direccionId"];
                    $direccion["numero"]=$cliente["direccionNumero"];
                    $direccion["calle"]=$cliente["direccionCalle"];
                    $direccion["piso"]=$cliente["direccionPiso"];
                    $direccion["depto"]=$cliente["direccionDepto"];
                    $direccion["notas"]=$cliente["direccionNotas"];


                    $clientes[$clienteId]["direcciones"][]=$direccion;
                }


            }

            $clientes=  array_values($clientes);
            $result["success"]=true;
            $result["data"]=$clientes;
        }
        else
        {
            $result["error"]=$db->errno;
        }



        break;

    case 'add':

        $validateData=validateData($_POST);


        if($validateData===true) {
            $sqlClientes = "REPLACE INTO clientes SET ";


            foreach ($_POST as $k=>$v)
            {
                if(!is_array($v)&&!empty($v))
                {
                    $sqlClientes.="{$k}='{$v}',";
                }
            }

            $sqlClientes=rtrim($sqlClientes,",");

            if($id)
            {
                $sqlClientes.=",clienteId={$id}";
            }


            $error=0;
            $db->query($sqlClientes);
            $error+=$db->errno;



            $clienteId=$db->insert_id;


            if(count($_POST["direcciones"])>0) {


                $sqlClientesDirecciones = "REPLACE INTO clientes_direcciones ( `cliente`, `direccion`,clientes_direcciones_order) values ";


                foreach ($_POST["direcciones"] as $direccion) {
                    $sqlDirecciones="REPLACE INTO direcciones SET ";

                    foreach ($direccion as $k => $v) {
                        if (!is_array($v)) {
                            if (!empty($v)) {
                                $sqlDirecciones .= "{$k}='{$v}',";
                            }

                        }

                    }
                    $sqlDirecciones = rtrim($sqlDirecciones, ",");



                    $db->query($sqlDirecciones);
                    $error+=$db->errno;



                    $sqlClientesDirecciones.= " ({$clienteId},{$db->insert_id},{$direccion["orden"]}),";


                }


                $sqlClientesDirecciones=rtrim($sqlClientesDirecciones,",");
                $db->query($sqlClientesDirecciones);
                $error+=$db->errno;



                if($error>0)
                {
                    $db->rollback();
                    $result["error"]=true;

                }
                else
                {

                    $result["success"]=$clienteId;


                }

                $commitResult=$db->commit();


                if(!$commitResult)
                {
                    $result["error"]=$db->errno;
                }


                $result["sql"][]=$sqlClientes;
                $result["sql"][]=$sqlClientesDirecciones;
                $result["sql"][]=$sqlDirecciones;
                $db->close();

            }



        }
        else
        {
            $result["error"]=$validateData;
        }

        break;

    case 'del':

        $sql ="DELETE FROM paradas WHERE paradaId={$id}";

        if($res = $db->query($sql))
        {
            $result["success"]=true;
        }
        else
        {
            $result["error"]=$db->errno;
        }

        break;
}


echo json_encode($result);


