<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
 * Reporte relato
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2011 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: relato.php,v 1.5.2.1 2011/09/14 14:56:19 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Exporta relato
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";
require_once "misc.php";


/*** Validaci�n y Relato ***/

hace_consulta($db, "INSERT INTO rango_edad_comunidad (id_caso, id_grupoper, id_rango) VALUES ('1', '1', '6');", false, false);

$post = array();


$_POST['_qf_consultaWeb_consulta'] = 'Consulta';
$_POST['_qf_default'] = 'consultaWeb:consulta';
$_POST['nomvic'] = '';
$_POST['caso_fecha'] = '1';
$_POST['caso_id'] = '1';
$_POST['caso_memo'] = '1';
$_POST['ffin']['M'] = '';
$_POST['ffin']['Y'] = '';
$_POST['ffin']['d'] = '';
$_POST['fini']['M'] = '';
$_POST['fini']['Y'] = '';
$_POST['fini']['d'] = '';
$_POST['id_casos'] = '';
$_POST['id_departamento'] = '';
$_POST['m_fuentes'] = '1';
$_POST['m_localizacion'] = '1';
$_POST['m_presponsables'] = '1';
$_POST['m_tipificacion'] = '1';
$_POST['m_varlineas'] = '1';
$_POST['m_victimas'] = '1';
$_POST['m_ubicacion'] = '1';
$_POST['mostrar'] = 'relato';
$_POST['ordenar'] = 'fecha';
$_POST['presponsable'] = '';
$_POST['retroalimentacion'] = '1';
$_POST['ssocial'] = '';
$_POST['titulo'] = '';
$_POST['usuario'] = '';

//$_POST = $post;
$_REQUEST = $_POST;
$_REQUEST['evita_csrf'] = $_SESSION['sin_csrf'] = $_POST['evita_csrf'] = 1234;

require "consulta_web.php";

?>
