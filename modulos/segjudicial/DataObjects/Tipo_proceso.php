<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Objeto tabla tipo_proceso
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: Tipo_proceso.php,v 1.13.2.2 2011/10/22 12:55:08 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla tipo_proceso
 * Ver documentaci�n de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Tipo_proceso extends DataObjects_Basica
{
    var $__table = 'tipo_proceso';                         // table name
    var $observaciones;                        // varchar(-1)  not_null

    var $nom_tabla = 'Tipo de proceso';

    var $fb_preDefOrder = array(
        'id',
        'nombre',
        'observaciones',
        'fechacreacion',
        'fechadeshabilitacion',
    );

    var $fb_fieldsToRender = array(
        'nombre',
        'observaciones',
        'fechacreacion',
        'fechadeshabilitacion',
    );
 
    /**
     * Identificacion de registro 'SIN INFORMACI�N'
     *
     * @return integer Id del registro SIN INFORMACI�N
     */
    static function idSinInfo()
    {
        return 1;
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

        $gr = array();
        $sel =& $form->getElement('nombre');
        $sel->setSize(30);
        $sel->setMaxlength(150);
        $gr[] =& $sel;

        $sel =& $form->getElement('observaciones');
        $sel->setSize(30);
        $sel->setMaxlength(500);
        $gr[] =& $sel;
    }
}

?>
