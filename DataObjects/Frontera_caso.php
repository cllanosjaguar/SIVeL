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
 * @version   CVS: $Id: Frontera_caso.php,v 1.13.2.2 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Definicion para la tabla frontera_caso.
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla frontera_caso.
 * Ver documentaci�n de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Frontera_caso extends DB_DataObject_SIVeL
{
    // START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'frontera_caso';                   // table name
    var $id_frontera;                     // int4(4)  multiple_key
    var $id_caso;                         // int4(4)  multiple_key


    /* the code above is auto generated do not remove the tag below */
    // END_AUTOCODE

    var $fb_preDefOrder = array('id_frontera');
    var $fb_fieldsToRender = array('id_frontera');
//    var $fb_hidePrimaryKey=false;
    var $fb_addFormHeader = false;
    var $fb_excludeFromAutoRules = array('id_frontera');
    var $fb_hidePrimaryKey = false;
    var $fb_fieldLabels = array(
            'id_frontera' => 'Frontera'
        );


    /**
     * Prepara antes de generar formulario.
     *
     * @param object &$formbuilder Generador DataObject_FormBuilder
     *
     * @return void
     */
    function preGenerateForm(&$formbuilder)
    {
        $this->fb_preDefElements = array('id_caso' =>
                    HTML_QuickForm::createElement('hidden', 'id_caso')
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
        $sel =& $form->getElement('id_frontera');
        if (isset($sel) && !PEAR::isError($sel)
            && $sel->getType() == 'select'
        ) {
            $sel->setSize('5');
            $sel->setMultiple(true);
            if (isset($GLOBALS['etiqueta']['frontera'])) {
                $sel->setLabel($GLOBALS['etiqueta']['frontera']);
            }
//            $sel->_options = htmlentities_array($sel->_options);
        }
        unset($form->_rules['id_frontera']);
    }

}

?>
