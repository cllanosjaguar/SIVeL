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
 * @version   CVS: $Id: Clase.php,v 1.17.2.1 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Definicion para la tabla clase.
 */
require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla clase.
 * Ver documentaci�n de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Basica
 */
class DataObjects_Clase extends DataObjects_Basica
{
    var $__table = 'clase';                           // table name
    var $id_municipio;                    // int4(4)  multiple_key
    var $id_tipo_clase;                   // varchar(-1)
    var $id_departamento;                 // int4(4)  multiple_key

    var $nom_tabla = 'Clase';

    var $fb_select_display_field = 'nombre';
    var $fb_linkDisplayFields = array('nombre', 'id_departamento',
        'id_municipio'
    );
    var $fb_preDefOrder = array(
        'nombre', 'id_municipio',
        'id_tipo_clase',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsToRender = array(
        'nombre', 'id_municipio',
        'id_tipo_clase',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldLabels = array(
        'nombre' => 'Nombre',
        'id_departamento' => 'Departamento',
        'id_municipio'=>'Municipio',
        'id_tipo_clase'=>'Tipo Clase',
        'fechacreacion' => 'Fecha de Creaci�n',
        'fechadeshabilitacion' => 'Fecha de Deshabilitaci�n',
    );
    var $fb_hidePrimaryKey = false;


    /**
     * Convierte valor de base a formulario.
     *
     * @param string &$valor Valor en base
     *
     * @return Valor para formulario
     */
    function getid_municipio(&$valor)
    {
        $valor = $this->id_municipio."-" . $this->id_departamento;
        return $valor;
    }

    /**
     * Pone un valor en la base diferente al recibido del formulario.
     *
     * @param string $valor Valor en formulario
     *
     * @return Valor para BD
     */
    function setid_municipio($valor)
    {
        // Si se cambia la identificaci�n del municipio, se cambia la
        // llave por lo que m�s bien debe hacerse una eliminaci�n y
        // despu�s a�adir uno nuevo.
        $v = explode("-", $valor);
        $this->id_municipio = $v[0];
        $this->id_departamento = $v[1];
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

        parent::preGenerateForm($formbuilder);
        $s =& HTML_QuickForm::createElement(
            'select', 'id_municipio',
            'Municipio: ', array()
        );
        $db = $this->getDatabaseConnection();
        $result = hace_consulta(
            $db, "SELECT municipio.id, municipio.id_departamento, " .
            "municipio.nombre, departamento.nombre " .
            "FROM municipio, departamento " .
            " WHERE id_departamento=departamento.id " .
            " ORDER BY municipio.nombre, departamento.nombre"
        );
        $options = array();
        $row = array();
        while ($result != null && !PEAR::isError($result)
            && $result->fetchInto($row)
        ) {
            $options[$row[0] . "-" . $row[1]] = $row[2] . " (" . $row[3] . ")";
        }
        $k = $this->id_municipio . "-" . $this->id_departamento;
        $s->loadArray($options, $k);

        $this->fb_preDefElements = array('id_municipio' => $s,
            'id_departamento' => HTML_QuickForm::createElement(
                'hidden',
                'id_departamento'
            ),
            'id' => HTML_QuickForm::createElement('hidden', 'id')
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
        parent::postGenerateform($form, $formbuilder);

        if (isset($this->id_municipio)) {
            $h =& $form->getElement('id_municipio');
            $k= $this->id_municipio."-" . $this->id_departamento;
            $h->setValue($k);
        }
    }

}

?>
