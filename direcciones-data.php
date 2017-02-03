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

        $sql="SELECT c.*,cd.clientes_direcciones_order,d.* FROM direcciones d LEFT JOIN clientes_direcciones cd ON cd.direccion=d.direccionId LEFT JOIN clientes c ON c.clienteId=cd.cliente  ";
        if($id)
        {
            $sql.=" WHERE d.direccionId={$id}";
        }


        $direcciones=array();

        if($res=$db->query($sql))
        {
            $res=$res->fetch_all(1);


            foreach($res as $direccion)
            {

               $direcciones[$direccion["direccionId"]]["calle"]=$direccion["direccionCalle"];
                $direcciones[$direccion["direccionId"]]["numero"]=$direccion["direccionNumero"];

                $direcciones[$direccion["direccionId"]]["piso"]=$direccion["direccionPiso"];

                $direcciones[$direccion["direccionId"]]["depto"]=$direccion["direccionDepto"];
                $direcciones[$direccion["direccionId"]]["notas"]=$direccion["direccionNotas"];


                if($direccion["clienteId"])
                {
                   $cliente["nombre"]=$direccion["clienteNombre"];
                    $cliente["apellido"]=$direccion["clienteApellido"];
                    $cliente["notas"]=$direccion["clienteNotas"];
                    $cliente["creacion"]=$direccion["clienteCreacion"];
                    $cliente["modificacion"]=$direccion["clienteModificacion"];
                    $cliente["id"]=$direccion["clienteId"];

                    $direcciones[$cliente["id"]]["clientes"][]=$cliente;


                }




            }


            $direcciones=  array_values($direcciones);
            $result["success"]=true;
            $result["data"]=$direcciones;
        }
        else
        {
            $result["error"]=$db->error;
        }



        break;

    case 'add':

        $validateData=validateData($_POST) ;
        if($validateData===true)
        {
            $sql="REPLACE INTO direcciones SET ";

            foreach ($_POST as $k=>$v)
            {
                if(!is_array($v)&&!empty($v))
                {
                        $sql.="{$k}='{$v}',";


                }

            }
            $sql=rtrim($sql,",");

            if($id)
            {
                $sql.=",direccionId={$id}";
            }

            $result["sql"]=$sql;
            if($res = $db->query($sql))
            {
                        $result["success"]=$db->insert_id;

            }
            else
            {
                $result["error"]=$db->error;
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


