<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Objeto asociado a una tabla b�sica t�pica de la base de datos.
 * Parcialmente generado por DB_DataObject.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2009 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: Basica.php,v 1.12.2.2 2011/10/22 12:55:07 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Definicion para una tabla b�sica.
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para una tabla b�sica.
 * Ver documentaci�n de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Basica extends DB_DataObject_SIVeL
{
    // START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'basica';                       // table name
    var $id;                            // int4(4)  not_null primary_key
    var $nombre;                        // varchar(-1)  not_null
    var $fechacreacion;                 // date(4)
    var $fechadeshabilitacion;          // date(4)

    var $nom_tabla = 'B�sica';

    var $fb_linkDisplayFields = array('nombre');
    var $fb_select_display_field = 'nombre';
    var $fb_selectAddEmpty = array('fechadeshabilitacion');
    var $fb_hidePrimaryKey = true;
    var $fb_fieldLabels = array(
        'id' => 'Identificaci�n',
        'nombre' => 'Nombre',
        'fechacreacion' => 'Fecha de creaci�n',
        'fechadeshabilitacion' => 'Fecha de deshabilitaci�n',
    );
    var $fb_preDefOrder = array(
        'id',
        'nombre',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsToRender = array(
        'nombre',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsRequired = array(
        'nombre',
        'fechacreacion',
    );
    var $fb_linkOrderFields = array(
        'nombre',
    );



    /**
     * Pone un valor en la base diferente al recibido del formulario.
     *
     * @param string $valor Valor en formulario
     *
     * @return Valor para BD
     */
    function setfechadeshabilitacion($valor)
    {
        if ($valor == "0000-00-00") {
            echo "1";
            $nv = 'null';
        } else {
            $nv = $valor;
        }
        $this->fechadeshabilitacion = $nv;

    }

    /**
     * Opciones de fecha para un campo
     *
     * @param string &$field campo
     *
     * @return arreglo de opciones
     */
    function dateOptions(&$field)
    {
        return array('language' => 'es',
        'format' => 'dMY',
        'minYear' => $GLOBALS['anio_min'],
        'maxYear' => 2025
        );
    }

    /**
     * Ajusta formulario generado.
     *
     * @param object &$form        Formulario HTML_QuickForm
     * @param object &$formbuilder Generador DataObject_FormBuilder
     *
     * @return void
     */
    function postGenerateForm(&$form, &$formbuilder)
    {
        parent::postGenerateForm($form, $formbuilder);
    }

    /**
     * Identificacion de registro 'SIN INFORMACI�N'
     *
     * @return integer Id del registro SIN INFORMACI�N
     */
    static function idSinInfo()
    {
        return -1;
    }

    /**
     * Validaciones adicionales a valores pasados por el formulario y que
     * se pretenden agregar a la base
     *
     * @param array $values  Valores pasados por formulario de la tabla b�sica
     *
     * @return bool da verdadero si y solo si pasa validaciones adicionales, si
     * no pasa validaciones debe presentar raz�n con echo
     */
    static function masValidaciones($values)
    {
        return true;
    }

}
?>
