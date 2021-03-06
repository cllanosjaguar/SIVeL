<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
* Cierra sesión de Sivel
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: terminar.php,v 1.24.2.1 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: CONSULTA PÚBLICA
*/


/**
 * Cierra sesión de Sivel
 */
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once 'misc.php';

cierraSesion($dsn);
echo "<html><head><title>SIVeL: Sistema de Información de Violencia " .
    "Política en Línea</title></head>";
echo "<body>";
echo "<h1>SIVeL: Sistema de Información de Violencia Política en Línea</h1>";
echo "Fin de sesión<br>";
echo '<a href = "index.php">Iniciar nueva sesión</a>';
echo "</body>";
echo "</html>";
?>
