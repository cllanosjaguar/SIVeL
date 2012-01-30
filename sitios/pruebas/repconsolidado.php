<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
* Reporte revista.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: repconsolidado.php,v 1.5.2.1 2011/09/14 14:56:19 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Reporte revista.
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

/*** Validaci�n y Reporte General ***/

$post = array();


$_POST['_qf_default'] = 'consolidado:consulta';
$_POST['id_departamento'] = '';
$_POST['fini']['d'] = '';
$_POST['fini']['M'] = '';
$_POST['fini']['Y'] = '';
$_POST['ffin']['d'] = '';
$_POST['ffin']['M'] = '';
$_POST['ffin']['Y'] = '';
$_POST['finchasta']['d'] = '';
$_POST['finchasta']['M'] = '';
$_POST['finchasta']['Y'] = '';
$_POST['muestra'] = 'tabla';


//$_POST = $post;
$_REQUEST = $_POST;
$_REQUEST['evita_csrf'] = $_SESSION['sin_csrf'] = $_POST['evita_csrf'] = 1234;

require "consolidado.php";

?>
