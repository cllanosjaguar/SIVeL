<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
 * Prueba Autenticaci�n
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2010 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: autentica.php,v 1.6.2.2 2011/10/11 16:33:37 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Prueba autenticaci�n
 */

if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}

require_once "ambiente.php";
require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once "misc.php";
require_once "misc_caso.php";
require_once "DB/DataObject/FormBuilder.php";

die("x");
$aut_usuario = "";
autenticaUsuario($dsn, $accno, $aut_usuario, 11);

echo "Si paso autenticausuario\n";

/* Verificando */
exit(0);
?>
