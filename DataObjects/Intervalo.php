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
 * @version   CVS: $Id: Intervalo.php,v 1.12.2.3 2011/10/22 14:58:19 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Definicion para la tabla intervalo.
 */
require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla intervalo.
 * Ver documentaci�n de DataObjects_Basica.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Basica
 */
class DataObjects_Intervalo extends DataObjects_Basica
{
    var $__table = 'intervalo';                       // table name
    var $rango;                           // varchar(-1)  not_null

    var $fb_linkDisplayFields = array('nombre');
    var $fb_hidePrimaryKey = true;

    var $nom_tabla = 'Intervalo';
    var $fb_preDefOrder = array(
        'nombre',
        'rango',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsToRender = array(
        'nombre',
        'rango',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsRequired = array(
        'nombre',
        'rango',
        'fechacreacion',
    );


    /**
     * Identificacion de registro 'SIN INFORMACI�N'
     *
     * @return string Identificaci�n
     */
    static function idSinInfo()
    {
        return 5;
    }


}
?>
