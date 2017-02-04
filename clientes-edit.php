<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 04/02/2017
 * Time: 12:26 AM
 */

$isEdit=true;

$site="clientes";
$action="add";


if(!is_numeric($_GET["id"]))
{
    header('Location: /');
    exit();
}

$urlSave="clientes-data.php?act=add&id={$_GET["id"]}";
$urlOnSave="clientes.php";


require("/includes/autoload.php");
require("/includes/templates/estructura.php");


