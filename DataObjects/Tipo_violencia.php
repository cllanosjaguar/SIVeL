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
 * @version   CVS: $Id: Tipo_violencia.php,v 1.14.2.3 2011/10/22 14:58:19 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Definicion para la tabla tipo_violencia.
 */
require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla tipo_violencia.
 * Ver documentaci�n de DataObjects_Basica.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Basica
 */
class DataObjects_Tipo_violencia extends DataObjects_Basica
{
    var $__table = 'tipo_violencia';                  // table name
    var $nomcorto;                          // varchar(-1)  not_null

    var $nom_tabla = 'Tipo de violencia';

    var $fb_dateFields = array('fechacreacion', 'fechadeshabilitacion');
    var $fb_linkDisplayFields = array('nombre');
    var $fb_selectAddEmpty = array('fechadeshabilitacion');
    var $fb_select_display_field = 'nomcorto';
    var $fb_hidePrimaryKey = false;
    var $fb_preDefOrder = array(
        'id',
        'nombre',
        'nomcorto',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsToRender = array(
        'id',
        'nombre',
        'nomcorto',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsRequired = array(
        'id',
        'nombre',
        'nomcorto',
        'fechacreacion',
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
        $this->fechadeshabilitacion = ($valor == '0000-00-00') ? 'null' : $valor;
    }

    /**
     * Convierte valor de base a formulario.
     *
     * @param string $valor Valor en base
     *
     * @return Valor para formulario
     */
    function getfechadeshabilitacion($valor)
    {
        $this->fechadeshabilitacion = ($valor == '0000-00-00') ? 'null' : $valor;
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
     * @param object &$form      Formulario HTML_QuickForm
     * @param object &$formbuilder Generador DataObject_FormBuilder
     *
     * @return void
     */
    function postGenerateForm(&$form, &$formbuilder)
    {
        parent::postGenerateForm($form, $formbuilder);
/*        $h =& $form->getElement('__header__');
        if (PEAR::isError($h)) {
            $h =& $form->getElement(null);
        }

        $h->setValue($this->nom_tabla);
        $f =& $form->getElement('fechadeshabilitacion');
        if (!isset($this->fechadeshabilitacion)
            || $this->fechadeshabilitacion == ''
        ) {
            $f->_elements[0]->setValue('');
            $f->_elements[1]->setValue('');
            $f->_elements[2]->setValue('');
        } */
    }

}

?>
