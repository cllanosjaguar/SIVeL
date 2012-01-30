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
 * @copyright 2009 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: Tipo_sitio.php,v 1.14.2.1 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Definicion  para la tabla tipo_sitio
 */
require_once 'DB_DataObject_SIVeL.php';
require_once 'HTML/QuickForm/Action.php';


/**
 * Definicion para la tabla tipo_sitio
 * Ver documentaci�n de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Tipo_sitio extends DataObjects_Basica
{
    var $__table = 'tipo_sitio';         // table name

    var $nom_tabla = 'Tipo de Sitio';

    /**
     * Identificacion de registro 'SIN INFORMACI�N'
     *
     * @return integer Id del registro SIN INFORMACI�N
     */
    static function idSinInfo()
    {
        return 1;
    }

}

?>
