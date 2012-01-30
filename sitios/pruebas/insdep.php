<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
* Inserci�n de departamento.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2006 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: insdep.php,v 1.7.2.1 2011/09/14 14:56:19 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Inserci�n de departamento.
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

$na = (int)$db->getOne("SELECT COUNT(nombre) FROM departamento;");

//echo "N�mero de departamentes antes: $na\n";
$_REQUEST['tabla'] = $_GET['tabla'] = 'departamento';
$_REQUEST['_qf__dataobjects_departamento'] = '';
$_POST['_qf__dataobjects_departamento'] = '';
$_REQUEST['id'] = $_POST['id'] = '';
$_REQUEST['nombre'] = $_POST['nombre'] = 'x';
$fc = array('d' => date('d'), 'M' => date('m'), 'Y' => date('Y'));
$_REQUEST['fechacreacion'] = $_POST['fechacreacion'] = $fc;
$_REQUEST['a�adir'] = $_POST['a�adir'] = 'A�adir';

$_SERVER['REQUEST_URI'] = 'pruebas';
$_REQUEST['evita_csrf'] = $_SESSION['sin_csrf'] = $_POST['evita_csrf'] = 1234;


require_once "PEAR.php";

$s =& PEAR::getStaticProperty('DB_DataObject','options');

require_once "detalle.php";

/* Verificando */
hace_consulta($db, "SELECT COUNT(nombre) FROM departamento;");
$nd = (int)$db->getOne("SELECT COUNT(nombre) FROM departamento;");

if (($nd-$na)!= 1) {
    echo "No insert�";
    exit(1);
}
exit(0);
?>
