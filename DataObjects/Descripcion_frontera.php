<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Objeto asociado a una tabla de la base de datos.
 * Parcialmente generado por DB_DataObject.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: Descripcion_frontera.php,v 1.8.2.1 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Definicion para la tabla descripcion_frontera.
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla descripcion_frontera.
 * Ver documentaci�n de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Descripcion_frontera extends DB_DataObject_SIVeL
{
    // START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'descripcion_frontera';            // table name
    var $id;                              // int4(4)  not_null primary_key
    var $id_frontera;                     // int4(4)
    var $lugar;                           // varchar(-1)  not_null
    var $sitio;                           // varchar(-1)
    var $tipo;                            // varchar(-1)

    /* the code above is auto generated do not remove the tag below */
    // END_AUTOCODE
}

?>
