<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
* Inserci�n de ubicaci�n de un caso
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: inscaso-ubicacion.php,v 1.4.2.1 2011/09/14 14:56:19 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Inserci�n de ubicaci�n de un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

/*** UBICACI�N ***/

$post = array();
$post['lugar'] = 'lugar';
$post['id_departamento'] = '1';
$post['id_municipio'] = '1';
$post['id_clase'] = '1';
$post['longitud'] = '10';
$post['latitud'] = '10';
$post['id_caso'] = '1';
$post['id_tipo_sitio'] = '1';
$post['sitio'] = 'sitio';
$post['_qf_ubicacion_siguienteMultiple'] = 'Ubicaci�n siguiente';
$post['_qf_default'] = 'ubicacion:siguiente';
pasaPestanaFicha($db, array("ubicacion"), $post, 1);

exit(0);
?>
