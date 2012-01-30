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
 * @version   CVS: $Id: Fuente_directa.php,v 1.11.2.2 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Definicion para la tabla fuente_directa.
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla fuente_directa.
 * Ver documentaci�n de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Fuente_directa extends DB_DataObject_SIVeL
{
    // START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'fuente_directa';                  // table name
    var $id;                              // int4(4)  not_null primary_key
    var $nombre;                          // varchar(-1)  not_null

    /* the code above is auto generated do not remove the tag below */
    // END_AUTOCODE
    var $fb_linkDisplayFields = array('nombre');
    var $fb_addFormHeader = false;
    var $fb_preDefOrder = array('nombre');
    var $fb_fieldsToRender = array('nombre');
    var $fb_hidePrimaryKey = false;
    var $fb_excludeFromAutoRules = array('nombre');
    var $fb_fieldLabels = array(
        'nombre' => 'Nombre'
    );


    /**
     * Prepara antes de generar formulario.
     *
     * @param object &$form Generador DataObject_FormBuilder
     *
     * @return void
     */
    function preGenerateForm(&$form)
    {
        $this->fb_preDefElements = array('id' =>
                     HTML_QuickForm::createElement('hidden', 'id')
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
        $e =& $form->getElement('nombre');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(70);
            $e->setMaxlength(200);
/*            if (isset($GLOBALS['etiqueta']['nombre'])) {
                $e->setLabel($GLOBALS['etiqueta']['nombre']);
} */
        }
    }

    /**
     * Prepara procesamiento de formulario diligenciado
     *
     * @param array  &$valores   Valores llenados por usuario
     * @param object &$formbuilder Generador DataObject_FormBuilder
     *
     * @return void
     */
    function preProcessForm(&$valores, &$formbuilder)
    {
        if ($this->id != null && (!isset($valores['id']) || $valores['id'] == '')) {
            $valores['id'] = $this->id;
        }
    }

}

?>
