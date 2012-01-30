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
 * @version   CVS: $Id: Rango_edad.php,v 1.13.2.2 2011/10/22 14:58:19 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Definicion para la tabla rango_edad.
 */
require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla rango_edad.
 * Ver documentaci�n de DataObjects_Basica.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Basica
 */
class DataObjects_Rango_edad extends DataObjects_Basica
{
    var $__table = 'rango_edad';                      // table name
    var $rango;                           // varchar(-1)  not_null
    var $limiteinferior;
    var $limitesuperior;

    var $nom_tabla = 'Rango de edad';

    var $fb_linkDisplayFields = array('rango');
    var $fb_select_display_field = 'rango';
    var $fb_linkOrderFields = array('nombre');
    var $fb_preDefOrder = array(
        'nombre',
        'rango',
        'limiteinferior',
        'limitesuperior',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsToRender = array(
        'nombre',
        'rango',
        'limiteinferior',
        'limitesuperior',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsRequired = array(
        'nombre',
        'rango',
        'limiteinferior',
        'limitesuperior',
        'fechacreacion',
    );



    /**
     * Nombres por presentar para cada campo.
     */
    var $fb_fieldLabels = array(
        'nombre' => 'Nombre',
        'Rango' => 'Rango',
        'limiteinferior' => 'L�mite Inferior',
        'limitesuperior' => 'L�mite Superior',
        'fechacreacion' => 'Fecha de Creaci�n',
        'fechadeshabilitacion' => 'Fecha de Deshabilitaci�n',
    );

    /**
     * Identificacion de registro 'SIN INFORMACI�N'
     *
     * @return int Id.
     */
    static function idSinInfo()
    {
        return 6;
    }

}

?>
