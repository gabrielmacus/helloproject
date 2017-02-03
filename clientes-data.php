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

                $clientes[$clienteId]["nombre"]=$cliente["clienteNombre"];
                $clientes[$clienteId]["apellido"]=$cliente["clienteApellido"];
                $clientes[$clienteId]["notas"]=$cliente["clienteNotas"];
                $clientes[$clienteId]["creacion"]=$cliente["clienteCreacion"];
                $clientes[$clienteId]["modificacion"]=$cliente["clienteModificacion"];

                if($cliente["direccionId"])
                {
                    $direccion["id"]=$cliente["direccionId"];
                    $direccion["numero"]=$cliente["direccionNumero"];
                    $direccion["calle"]=$parada["direccionCalle"];
                    $direccion["piso"]=$parada["direccionPiso"];
                    $direccion["depto"]=$parada["direccionDepto"];
                    $direccion["notas"]=$parada["direccionNotas"];


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

        $validateData=validateData($_POST) ;
        if($validateData===true)
        {
            $sql="REPLACE INTO clientes SET ";

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
                $sql.=",clienteId={$id}";
            }

            $result["sql"][]=$sql;
            $result["data"]=$_POST;
            if($res = $db->query($sql))
            {

                $clienteId=$db->insert_id;



                if(count($_POST["direcciones"])>0)
                {

                    foreach ($_POST["direcciones"] as $direccion)
                    {

                        $sql="REPLACE INTO direcciones SET ";

                        foreach ($direccion as $k=>$v)
                        {
                            if(!is_array($v))
                            {
                                if(!empty($v))
                                {
                                    $sql.="{$k}='{$v}',";
                                }

                            }

                        }
                        $sql=rtrim($sql,",");

                        if($direccion["id"])
                        {
                            $sql.=",direccionId={$id}";
                        }
                        $result["sql"][]=$sql;
                        if($res=$db->query($sql))
                        {
                            $sql="REPLACE INTO clientes_direcciones ( `cliente`, `direccion`,clientes_direcciones_order) values ";

                            $sql.=" ({$clienteId},{$db->insert_id},{$direccion["orden"]}),";




                            $sql=rtrim($sql,",");


                            $result["sql"][]=$sql;
                            if($res=$db->query($sql))
                            {
                                $result["success"]=$db->insert_id;
                            }
                            else
                            {
                                $result["error"]=$db->errno;
                            }
                        }
                        else
                        {
                            $result["error"]=$db->error;
                        }

                    }


                }
                else
                {
                    $result["success"]=$db->insert_id;

                }






            }
            else
            {
                $result["error"]=$db->errno;
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


