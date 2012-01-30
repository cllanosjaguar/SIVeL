<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * P�gina del multi-formulario para capturar caso (captura_caso.php).
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: PagEvaluacion.php,v 1.48.2.2 2011/10/11 16:33:37 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Pesta�a Evaluaci�n del multi-formulario capturar caso
 */
require_once 'PagBaseSimple.php';

/**
 * Evaluaci�n de la informaci�n.
 * Ver documentaci�n de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      PagBaseSimple
 */
class PagEvaluacion extends PagBaseSimple
{

    var $titulo = 'Evaluaci�n';

    var $clase_modelo = 'caso';

    /**
     * Constructora.
     * Ver documentaci�n completa en clase base.
     *
     * @param string $nomForma Nombre
     *
     * @return void
     */
    function PagEvaluacion($nomForma)
    {
        parent::PagBaseSimple($nomForma);

        $this->addAction('process', new Terminar());
        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
        ) {
            $this->addAction('siguiente', new Siguiente());
        }
        $this->addAction('anterior', new Anterior());
    }


    /**
     * Agrega elementos al formulario.
     * Ver documentaci�n completa en clase base.
     *
     * @param handle &$db    Conexi�n a base de datos
     * @param string $idcaso Id del caso
     *
     * @return void
     *
     * @see PagBaseSimple
     */
    function formularioAgrega(&$db, $idcaso)
    {
        $this->bcaso->_do->fb_fieldsToRender = array(
            'gr_confiabilidad',
            'gr_esclarecimiento', 'gr_impunidad', 'gr_informacion'
        );
        $this->bcaso->_do->fb_preDefOrder
            = $this->bcaso->_do->fb_fieldsToRender;
        $this->bcaso->createSubmit = 0;
        $this->bcaso->useForm($this);
        $this->bcaso->getForm();

        agrega_control_CSRF($this);

    }

    /**
     * Llena valores del formulario.
     * Ver documentaci�n completa en clase base.
     *
     * @param handle  &$db    Conexi�n a base de datos
     * @param integer $idcaso Id del caso
     *
     * @return void
     * @see PagBaseSimple
     */
    function formularioValores(&$db, $idcaso)
    {
        if (!isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda'
        ) {
            $this->controller->deshabilitaBotones($this, array('siguiente'));
        }
    }

    /**
     * Elimina registros de tablas relacionadas con caso de este formulario.
     * Ver documentaci�n completa en clase base.
     *
     * @param handle  &$db    Conexi�n a base de datos
     * @param integer $idcaso Id del caso
     *
     * @return void
     * @see PagBaseSimple
     */
    static function eliminaDep(&$db, $idcaso)
    {
    }

    /**
     * Prepara consulta SQL para buscar datos de este formulario.
     * Ver documentaci�n completa en clase base.
     *
     * @param string &$w       Consulta que se construye
     * @param string &$t       Tablas
     * @param object &$db      Conexi�n a base de datos
     * @param object $idcaso   Identificaci�n del caso
     * @param string &$subcons Subconsulta
     *
     * @return void
     * @see PagBaseSimple
     */
    function datosBusqueda(&$w, &$t, &$db, $idcaso, &$subcons)
    {
        $dCaso = objeto_tabla('caso');
        $dCaso->id = $idcaso;
        assert($dCaso->find() != 0);
        $dCaso->fetch();

        consulta_and(
            $db, $w, "caso.gr_confiabilidad",
            $dCaso->gr_confiabilidad
        );
        consulta_and(
            $db, $w, "caso.gr_esclarecimiento",
            $dCaso->gr_esclarecimiento
        );
        consulta_and(
            $db, $w, "caso.gr_impunidad",
            $dCaso->gr_impunidad
        );
        consulta_and(
            $db, $w, "caso.gr_informacion",
            $dCaso->gr_informacion
        );
    }

}
?>
