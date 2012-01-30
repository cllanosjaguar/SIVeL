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
 * @version   CVS: $Id: Victima_colectiva.php,v 1.18.2.1 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Definicion para la tabla victima_colectiva.
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla victima_colectiva.
 * Ver documentaci�n de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Victima_colectiva extends DB_DataObject_SIVeL
{
    // START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'victima_colectiva';               // table name
    var $id_grupoper;                     // int4(4)  not_null primary_key
    var $id_caso;                     // int4(4)  not_null primary_key
    var $personas_aprox;                  // int4(4)
    var $id_organizacion_armada;          // int4(4)

    /* the code above is auto generated do not remove the tag below */
    // END_AUTOCODE
    var $fb_preDefOrder = array('personas_aprox', 'id_organizacion_armada', );
    var $fb_fieldsToRender = array('personas_aprox',
        'id_organizacion_armada'
    );
    var $fb_addFormHeader = false;
    var $fb_fieldLabels = array(
        'id_organizacion_armada'=> 'Organizaci�n Armada V�ctima',
        'personas_aprox' => 'Num. Aprox. Personas',
    );
    var $fb_hidePrimaryKey = true;

    /**
     * Pone un valor en la base diferente al recibido del formulario.
     *
     * @param string $value Valor en formulario
     *
     * @return Valor para BD
     */
    function setpersonas_aprox($value)
    {
        $this->personas_aprox= ($value == '') ? 'null' : $value;
    }

    /**
     * Prepara antes de generar formulario.
     *
     * @param object &$formbuilder Generador DataObject_FormBuilder
     *
     * @return void
     */
    function preGenerateForm(&$formbuilder)
    {
        if (!isset($this->id_organizacion_armada)) {
            include_once "Presuntos_responsables.php";
            $this->id_organizacion_armada =
                DataObjects_Presuntos_responsables::idSinInfo();
        }
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

        $sel =& $form->getElement('personas_aprox');
        if (isset($sel) && !PEAR::isError($sel)) {
            $sel->setSize(5);
        }
        if (isset($this->personas_aprox) && $this->personas_aprox == 'null') {
            $sel->setValue('');
        }
    }

    /**
     * Cadena para un texto XML que identifica el registro
     **/
    function valorRelato()
    {
        return $this->id_grupoper;
    }

    /**
     * Arreglo para ayudar a conversi�n entre base de datos y relato
     *
     * @return array Cada elemento es tabla->array(etiqueta, campo_bd)
     */
    function tradRelato()
    {
        return array(
            'vinculo_estado_comunidad' =>
            array('vinculo_estado_comunidad', 'id_vinculo_estado'),
            'profesion_comunidad' =>
            array('profesion', 'id_profesion'),
            'antecedente_comunidad' =>
            array('antecedente', 'id_antecedente'),
            'filiacion_comunidad' =>
            array('filiacion', 'id_filiacion'),
            'organizacion_comunidad' =>
            array('organizacion', 'id_organizacion'),
            'rango_edad_comunidad' =>
            array('rango_edad', 'id_rango'),
            'rango_edad_comunidad' =>
            array('rango_edad', 'id_rango'),
            'sector_social_comunidad' =>
            array('sector_social', 'id_sector'),
        );
    }



}

?>
