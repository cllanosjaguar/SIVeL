<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
* Inserci�n de evaluaci�n un caso
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: inscaso-evaluacion.php,v 1.4.2.1 2011/09/14 14:56:19 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Inserci�n de evaluaci�n un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

/*** EVALUACI�N ***/

$post = array();
$post['gr_confiabilidad'] = 'Alta';
$post['gr_esclarecimiento'] = 'Alto';
$post['gr_impunidad'] = 'Nula';
$post['gr_informacion'] = 'Parc';
$post['_qf_evaluacion_basicos'] = 'Datos b�sicos';
$post['_qf_default'] = 'evaluacion:siguiente';
pasaPestanaFicha($db, array(), $post, 1);
assert(false); // No llega

exit(0);
?>
