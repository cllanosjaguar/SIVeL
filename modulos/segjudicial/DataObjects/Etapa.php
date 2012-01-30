<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Objeto tabla etapa
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: Etapa.php,v 1.11.2.3 2011/10/22 12:55:08 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla etapa
 * Ver documentaci�n de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Etapa extends DataObjects_Basica
{
    var $__table = 'etapa';                         // table name
    var $id_tipo;                              // int4(4)  not_null
    var $observaciones;                        // varchar(-1)  not_null

    var $nom_tabla = 'Etapa';

    var $fb_preDefOrder = array(
        'id',
        'id_tipo',
        'nombre',
        'observaciones',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsToRender = array(
        'id_tipo',
        'nombre',
        'observaciones',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_addFormHeader = false;
    var $fb_fieldsRequired = array(
        'id_tipo',
        'nombre',
        'fechacreacion',
    );
    var $fb_linkDisplayFields = array(
        'nombre',
        'id_tipo',
    );
    var $fb_fieldLabels = array(
        'id_tipo' => 'Tipo de proceso',
        'nombre' => 'Nombre',
        'observaciones' => 'Observaciones',
        'fechacreacion' => 'Fecha de creaci�n',
        'fechadeshabilitacion' => 'Fecha de deshabilitaci�n',
    );

     /**
     * Identificacion de registro 'SIN INFORMACI�N'
     *
     * @return string Identificaci�n
     */
    static function idSinInfo()
    {
        return 20;
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
