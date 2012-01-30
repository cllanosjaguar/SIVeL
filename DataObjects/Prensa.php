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
 * @version   CVS: $Id: Prensa.php,v 1.12.2.2 2011/10/22 14:58:19 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla prensa.
 * Ver documentaci�n de DataObjects_Basica.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Basica
 */
class DataObjects_Prensa extends DataObjects_Basica
{
    var $__table = 'prensa';                          // table name
    var $tipo_fuente;                     // varchar(-1)  not_null

    var $nom_tabla = 'Fuentes frecuentes';

    var $fb_fieldLabels= array(
        'tipo_fuente'=>'Tipo de Fuente',
        'fechacreacion' => 'Fecha de Creaci�n',
        'fechadeshabilitacion' => 'Fecha de Deshabilitaci�n',
    );
    var $fb_enumFields = array('tipo_fuente');
    var $es_enumOptions = array(
        'tipo_fuente' => array(
            'Directa' => 'Directa',
            'Indirecta' => 'Indirecta'
        )
    );
    var $fb_preDefOrder = array(
        'nombre',
        'tipo_fuente',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsToRender = array(
        'nombre',
        'tipo_fuente',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsRequired = array(
        'nombre',
        'tipo_fuente',
        'fechacreacion',
    );




    /**
     * Identificacion de registro 'SIN INFORMACI�N'
     *
     * @return integer Id
     */
    static function idSinInfo()
    {
        return 0;
    }

}

?>
