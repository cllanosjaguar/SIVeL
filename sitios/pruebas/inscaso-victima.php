<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
* Inserci�n de v�ctima de un caso
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: inscaso-victima.php,v 1.7.2.2 2011/10/22 12:51:51 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Inserci�n de v�ctima de un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

/*** V�CTIMA ***/

$post = array();
$post['id_departamento'] = '1';
$post['id_municipio'] = '1';
$post['id_clase'] = '1';
$post['tipodocumento'] = 'CC';
$post['numerodocumento'] = '1';
$post['nombres'] = 'nombres';
$post['apellidos'] = 'apellidos';
$post['anionac'] = '2006';
$post['mesnac'] = '1';
$post['dianac'] = '1';
$post['sexo'] = 'F';
$post['hijos'] = '3';
$post['id_profesion'] = '1';
$post['id_rango_edad'] = '1';
$post['id_filiacion'] = '1';
$post['id_sector_social'] = '1';
$post['id_organizacion'] = '1';
$post['id_organizacion_armada'] = '1';
$post['id_antecedente']['0'] = '1';
$post['id_vinculo_estado'] = '1';
$post['anotaciones'] = 'anotaciones';
$post['_qf_victimaIndividual_siguienteMultiple'] = 'Victima siguiente';
$post['_qf_default'] = 'victimaIndividual:siguiente';
pasaPestanaFicha(
    $db, array("persona",
    "victima", "antecedente_victima"
    ),
    $post, 1
);


exit(0);
?>
