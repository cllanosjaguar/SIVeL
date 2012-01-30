<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
* Inserci�n de fuentes frecuentes de un caso
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: inscaso-frecuentes.php,v 1.4.2.1 2011/09/14 14:56:19 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Inserci�n de fuentes frecuentes de un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

/*** FUENTES FRECUENTES ***/

$post = array();
$post['id_prensa'] = '1';
$post['fecha']['d'] = '10';
$post['fecha']['M'] = '10';
$post['fecha']['Y'] = '2007';
$post['ubicacion'] = 'ubicaci�n';
$post['clasificacion'] = 'clasificacion';
$post['ubicacion_fisica'] = 'ubicacion';
$post['_qf_frecuentes_siguienteMultiple'] = 'Fuente siguiente';
$post['_qf_default'] = 'frecuentes:siguiente';
pasaPestanaFicha($db, array("escrito_caso"), $post, 1);

exit(0);
?>
