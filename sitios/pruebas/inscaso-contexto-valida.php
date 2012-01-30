<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
 * Verifica inserci�n en pesta�a contexto
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2011 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: inscaso-contexto-valida.php,v 1.6.2.1 2011/09/14 14:56:19 vtamara Exp $
 * @link      http://sivel.sf.net
*/


// Necesario porque la pesta�a contexto termina pasando por la
// QuickForm/Actions/Jump que intenta enviar encabezado Location
// para llamar otra pagina y eso no es soportado en modo CLI.

if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

$nume = verificaInsercion($db, array('caso'), array('caso' => 0));
if ($nume > 0) {
    echo "Cantidad de errores $nume\n";
    exit(1);
}
exit(0);

?>
