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
 * @version   CVS: $Id: Usuario.php,v 1.13.2.2 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Definicion para la tabla usuario.
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla usuario.
 * Ver documentaci�n de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Usuario extends DB_DataObject_SIVeL
{
    // START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'usuario';                         // table name
    var $id_usuario;                      // varchar(-1)  not_null primary_key
    var $password;                        // varchar(-1)  not_null
    var $nombre;                          // varchar(-1)
    var $descripcion;                     // varchar(-1)
    var $id_rol;                          // int4(4)
    var $dias_edicion_caso;               // int4(4)

    /* the code above is auto generated do not remove the tag below */
    // END_AUTOCODE

    var $fb_fieldLabels = array(
        'id_usuario' => 'Identificaci�n',
        'password' => 'Clave',
        'nombre' => 'Nombre',
        'descripcion' => 'Descripcion',
        'id_rol' => 'Rol'
    );
    var $fb_preDefOrder = array('id_usuario', 'password', 'nombre',
        'descripcion', 'id_rol'
    );
    var $fb_fieldsToRender = array('id_usuario', 'password', 'nombre',
        'descripcion', 'id_rol'
    );
    var $fb_linkDisplayFields = array('id_usuario');
    var $fb_select_display_field= 'id_usuario';
    var $fb_hidePrimaryKey = false;

    /**
     * Pone un valor en la base diferente al recibido del formulario.
     *
     * @param string $value Valor en formulario
     *
     * @return Valor para BD
     */
    function setpassword($value)
    {
        if ($value == '') {
            $this->password = null;
        } else {
            $this->password = sha1($value);
        }
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
        $e = HTML_QuickForm::createElement(
            'password',
            'password', 'Clave'
        );
        $this->fb_preDefElements = array('password' => $e);
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
        $e =& $form->getElement('password');
        $e->setValue('');
    }

}

?>