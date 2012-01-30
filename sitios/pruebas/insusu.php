<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
* Inserci�n de un usuario
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: insusu.php,v 1.5.2.2 2011/10/11 16:33:37 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Inserci�n de un usuario
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

$na = (int)$db->getOne("SELECT COUNT(id_usuario) FROM usuario;");

echo "\nna=$na";
$_REQUEST = $_POST = $_GET = array();
$_REQUEST['_qf__dataobjects_usuario'] = $_POST['_qf__dataobjects_usuario'] = '';
$_REQUEST['id_usuario'] = $_POST['id_usuario'] = 'inv1';
$_REQUEST['password'] = $_POST['password'] = 'b';
$_REQUEST['nombre'] = $_POST['nombre'] = 'c';
$_REQUEST['descripcion'] = $_POST['descripcion'] = 'd';
$_REQUEST['id_rol'] = $_POST['id_rol'] = '1';
$_REQUEST['a�adir'] = $_POST['a�adir'] = 'A�adir';
$_REQUEST['evita_csrf'] = $_SESSION['sin_csrf'] = $_POST['evita_csrf'] = 1234;

$_SERVER['REQUEST_URI'] = 'pruebas';

require_once "detusyrol.php";


/* Verificando */
$nd = (int)$db->getOne("SELECT COUNT(id_usuario) FROM usuario;");

echo "insusu nd=$nd\n";

if (($nd-$na)!= 1) {
    echo "No insert�";
    exit(1);
}
exit(0);
?>
