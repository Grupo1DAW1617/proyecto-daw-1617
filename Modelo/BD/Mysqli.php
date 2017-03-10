<?php
/**
 * Created by PhpStorm.
 * User: 2gdaw11
 * Date: 10/3/17
 * Time: 11:19
 */

error_reporting(E_ERROR | E_WARNING | E_PARSE);
date_default_timezone_set('Europe/Madrid');
$dbhost = "localhost";
$dbname = "himevico";
$dbuser = "root";
$dbpass = "root";
$tabla = "";
$db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($db->connect_errno)
{
    die ("<h1>Fallo al conectar a MySQL: (" . $db->connect_errno . ") " . $db->connect_error."</h1>");

}