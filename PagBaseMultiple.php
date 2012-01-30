<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Base para p�gina con multiples subp�ginas al capturar caso
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2005 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: PagBaseMultiple.php,v 1.34.2.1 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Base para p�gina con multiples subp�ginas al capturar caso
 */
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once 'PagBaseSimple.php';
require_once 'HTML/QuickForm/Action.php';

/**
 * Acci�n que responde al bot�n eliminar.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class EliminarMultiple extends HTML_QuickForm_Action
{
    /**
     * Ejecuta acci�n
     *
     * @param object &$page      P�gina
     * @param string $actionName Acci�n
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        $page->elimina($page->_submitValues);
        if ($_SESSION[$page->pref.'_pag'] >= $_SESSION[$page->pref.'_total']) {
            $_SESSION[$page->pref.'_pag']
                = max($_SESSION[$page->pref.'_total']-1, 0);
        }
        $page->nullVar();
        $page->_submitValues = array();
        $page->_defaultValues = array();
        $page->handle('display');
    }
}

/**
 * Acci�n que responde al bot�n nuevo
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class NuevoMultiple extends HTML_QuickForm_Action
{
    /**
     * Ejecuta acci�n
     *
     * @param object &$page      P�gina
     * @param string $actionName Acci�n
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        if ($page->procesa($page->_submitValues)) {
            $page->_submitValues = array();
            $page->_defaultValues = array();
            $page->nullVar();
            $_SESSION[$page->pref.'_pag'] = $_SESSION[$page->pref.'_total'];
        }
        $page->handle('display');
    }
}

/**
 * Acci�n que responde al bot�n nuevo como copia
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class NuevoCopiaMultiple extends HTML_QuickForm_Action
{
    /**
     * Ejecuta acci�n
     *
     * @param object &$page      P�gina
     * @param string $actionName Acci�n
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        if ($page->procesa($page->_submitValues)) {
            $page->_submitValues = array();
            $page->_defaultValues = array();
            $page->iniVar();
            $_SESSION['nuevo_copia_id'] = $page->copiaId();
            $page->nullVar();
            $_SESSION[$page->pref.'_pag'] = $_SESSION[$page->pref.'_total'];
        }
        $page->handle('display');
    }
}


/**
 * Acci�n que responde al bot�n anterior
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class AnteriorMultiple extends HTML_QuickForm_Action
{
    /**
     * Ejecuta acci�n
     *
     * @param object &$page      P�gina
     * @param string $actionName Acci�n
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        if ($page->procesa($page->_submitValues)) {
            $page->_submitValues = array();
            $page->_defaultValues = array();
            if ($_SESSION[$page->pref.'_pag'] > 0) {
                $_SESSION[$page->pref.'_pag']--;
                $page->nullVar();
            }
        }
        $page->handle('display');
    }
}

/**
 * Acci�n que responde al bot�n siguiente
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class SiguienteMultiple extends HTML_QuickForm_Action
{

    /**
     * Ejecuta acci�n
     *
     * @param object &$page      P�gina
     * @param string $actionName Acci�n
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        if ($page->procesa($page->_submitValues)) {
            $page->_submitValues = array();
            $page->_defaultValues = array();
            if ($_SESSION[$page->pref.'_pag'] < $_SESSION[$page->pref.'_total']
            ) {
                $_SESSION[$page->pref.'_pag']++;
            }
        }
        $page->nullVar();
        $page->handle('display');
    }
}


/**
 * Clase base para p�gina con multiples subp�ginas al capturar caso.
 *
 * La �dea es identificar con un n�mero las posibles subp�ginas, para
 * poder avanzar, retroceder, eliminar y agregar nuevos.
 * La informaci�n de la subp�gina en la que est� se mantiene en variables
 * de sesi�n que tienen un prefijo com�n.
 *
 * Ver tambi�n documentaci�n de clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      PagBaseSimple
 */
abstract class PagBaseMultiple extends PagBaseSimple
{

    /** Titulo corto que aparece en botones */
    var $tcorto = '';

    /** Prefijo com�n para variables de sesi�n de la clase */
    var $pref = '';

    /** Habilitar boton Nueva Copia */
    var $nuevoCopia = true;

    /**
     * Pone en null variables asociadas a tablas de la pesta�a.
     *
     * @return null
     */
    abstract function nullVar();

    /**
     * Elimina de base de datos el registro actual.
     *
     * @param array &$valores Valores enviados por formulario.
     *
     * @return null
     */
    abstract function elimina(&$valores) ;

    /**
     * Retorna una identificaci�n del registro actual.
     *
     * @return string Identifaci�n
     */
    abstract function copiaId();

    /**
     * Constructora
     *
     * @param string $nomForma Nombre del formulario
     *
     * @return null
     */
    function PagBaseMultiple($nomForma)
    {
        parent::PagBaseSimple($nomForma);

        $this->addAction('eliminar', new EliminarMultiple());
        $this->addAction('nuevo', new NuevoMultiple());
        if ($this->nuevoCopia) {
            $this->addAction('nuevoCopia', new NuevoCopiaMultiple());
        }
        $this->addAction('anteriorMultiple', new AnteriorMultiple());
        $this->addAction('siguienteMultiple', new SiguienteMultiple());

        if (!isset($_SESSION[$this->pref . '_pag'])) {
            $_SESSION[$this->pref . '_pag'] = 0;
        }
    }


    /**
     * Construye elementos del formulario incluyendo botones
     * (anterior/siguiente/eliminar/nuevo/nueva copia)
     *
     * @return Formulario
     */
    function buildForm()
    {
        $this->_formBuilt = true;
        $this->_submitValues = array();
        $this->_defaultValues = array();

        $cm = "b" . $this->clase_modelo;
        if (!isset($this->$cm) || $this->$cm == null) {
            $db = $this->iniVar();
        } else {
            $db = $this->$cm->_do->getDatabaseConnection();
        }
        $this->controller->creaTabuladores($this, array('class' => 'flat'));
        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die("Bug: idcaso no deber�a ser null");
        }

        $comp = $idcaso == $GLOBALS['idbus'] ? 'Consulta' : 'Caso ' . $idcaso;
        $nf = $_SESSION[$this->pref.'_pag'] >= $_SESSION[$this->pref.'_total'] ?
            '-' : $_SESSION[$this->pref . '_pag'] + 1;
        $e =& $this->addElement(
            'header', null, '<table width = "100%">' .
            '<th align = "left">' . $this->titulo . ' (' .
            $nf .'/' . $_SESSION[$this->pref . '_total'] .
            ')</th><th algin = "right">' .
            $comp . "</th></table>"
        );


        $nac = 'eliminar';
        $n = $this->getButtonName($nac);
        $e =& $this->createElement('submit', $n, 'Eliminar');
        $ed[] =& $e;

        $nac = 'nuevo';
        $n = $this->getButtonName($nac);
        $e =& $this->createElement('submit', $n, 'Nueva');
        $ed[] =& $e;

        $nac = 'nuevoCopia';
        $n = $this->getButtonName($nac);
        $e =& $this->createElement('submit', $n, 'Nueva Copia');
        if (!$this->nuevoCopia) {
            $e->updateAttributes(array('disabled' => 'true'));
        }
        $ed[] =& $e;

        $nac = 'anteriorMultiple';
        $n = $this->getButtonName($nac);
        $e =& $this->createElement('submit', $n, $this->tcorto.' anterior');
        $ed[] =& $e;

        $nac = 'siguienteMultiple';
        $n = $this->getButtonName($nac);
        $e =& $this->createElement('submit', $n, $this->tcorto.' siguiente');
        $ed[] =& $e;

        $this->addGroup($ed, null, '', '&nbsp;', false);

        $this->formularioAgrega($db, $idcaso);

        $this->controller->creaBotonesEstandar($this);

        $this->setDefaultAction('siguiente');

        $this->formularioValores($db, $idcaso);
    }

}

?>
