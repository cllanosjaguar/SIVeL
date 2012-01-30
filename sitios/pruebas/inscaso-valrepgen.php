<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
* Inserci�n de evaluaci�n y paso a reporte general en un caso
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: inscaso-valrepgen.php,v 1.4.2.2 2011/10/18 16:05:05 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Inserci�n de evaluaci�n y paso a reporte general en un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";
require_once "PagBasicos.php";

/*** Validaci�n y Reporte General ***/

ReporteGeneral::reporte(1);

exit(0);
?>
