<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
 * Inserci�n de acto
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2011 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: inscaso-acto.php,v 1.4.2.1 2011/09/14 14:56:19 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Inserci�n de un acto en un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

$post = array();
$post['presponsables'][0] = '1';
$post['categorias'][0] = '1000';
$post['victimas'][0] = '1';
$post['id_caso'] = '1';
$post['_qf_acto_agregarActo'] = 'A�adir';
$post['_qf_default'] = 'acto:siguiente';

pasaPestanaFicha($db, array("acto"), $post, 1);

exit(0);
?>
