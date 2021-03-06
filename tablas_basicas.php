<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
* Tablas básicas
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: tablas_basicas.php,v 1.26 2011/05/19 04:18:44 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Presenta tablas básicas
 */
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once 'misc.php';
require_once 'misc_caso.php';

$aut_usuario = "";
autenticaUsuario($dsn, $accno, $aut_usuario, 11);

encabezado_envia("Tablas básicas");

require_once 'HTML/Menu.php';
require_once 'HTML/Menu/DirectTreeRenderer.php';

actGlobales();

$menu =& new HTML_Menu($GLOBALS['menu_tablas_basicas'], 'sitemap');
$rend =& new HTML_Menu_DirectTreeRenderer();

$rend->setEntryTemplate(
    HTML_MENU_ENTRY_INACTIVE,
    '<a href = "tabla.php?tabla={url}">{title}</a>'
);
$menu->render($rend);
echo '<table border = "0" width = "100%"><tr>' .
    '<td style = "white-space: nowrap;background-color:#CCCCCC;" ' .
    'align="center" valign="top" colspan="2"><b>Tablas Básicas</b>' .
    '</td></tr></table>';
print $rend->toHtml();

echo '<pr>&nbsp;</pr><table border="0" width="100%" ' .
    'style="white-space: nowrap; background-color:#CCCCCC;"><tr>' .
    '<td align="right">' .
    '<a href="index.php"><b>Menú Principal</b></a>' .
    '</td></tr></table>';
    pie_envia();
?>
