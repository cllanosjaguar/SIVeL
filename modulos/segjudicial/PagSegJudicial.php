<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 *P�gina del multi-formulario para capturar caso (captura_caso.php).
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: PagSegJudicial.php,v 1.22.2.7 2011/10/22 13:01:23 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

require_once 'PagBaseMultiple.php';
require_once 'ResConsulta.php';
require_once 'HTML/QuickForm/Action.php';

require_once 'DataObjects/Accion.php';
require_once 'DataObjects/Rango_edad.php';
require_once 'DataObjects/Sector_social.php';
require_once 'DataObjects/Vinculo_estado.php';
require_once 'DataObjects/Filiacion.php';
require_once 'DataObjects/Organizacion.php';
require_once 'DataObjects/Profesion.php';
require_once 'DataObjects/Presuntos_responsables.php';
require_once 'DataObjects/Resultado_agresion.php';
require_once 'DataObjects/Etapa.php';
require_once 'DataObjects/Tipo_proceso.php';



/**
 * Acci�n que responde al botor Agregar Acci�n
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 */
class AgregarAccionJ extends HTML_QuickForm_Action
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
        if ($page->procesa($page->_submitValues, true)) {
            $page->_submitValues = array();
            $page->_defaultValues = array();
            $page->nullVar();
        }
        $page->handle('display');
    }
}


/**
* Responde a eliminaci�n de una acci�n
 * @package SIVeL
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  Dominio p�blico.
 * @link     http://sivel.sf.net/tec
*/
class EliminaAccionJ extends HTML_QuickForm_Action
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
        assert($_REQUEST['eliminaaccionj'] != null);
        $dac=& objeto_tabla('accion');
        $dac->id = (int)$_REQUEST['eliminaaccionj'];
        $dac->delete();
        $page->_submitValues = array();
        $page->_defaultValues = array();
        $page->nullVar();
        $page->handle('display');
    }
}


/**
* P�gina Proceso Judicial
* Ver documentaci�n de funciones en clase base.
 * @package SIVeL
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  Dominio p�blico.
 * @link     http://sivel.sf.net/tec
*/
class PagSegJudicial extends PagBaseMultiple
{

    var $bproceso;
    var $baccion;

    var $titulo = 'Seguimiento Judicial';

    var $tcorto = 'Seg. Jud.';

    var $pref = "fju";

    var $nuevaCopia = false;

    var $clase_modelo = 'proceso';

    /**
     * Pone en null variables asociadas a tablas de la pesta�a.
     *
     * @return null
     */
    function nullVar()
    {
        $this->bproceso = null;
        $this->baccion = null;
    }

    /**
     * Retorna una identificaci�n del registro actual.
     *
     * @return string Identifaci�n
     */
    function copiaId()
    {
        return $this->bproceso->_do->id;
    }

    function elimina(&$values)
    {
        $this->iniVar();
        if (isset($this->bproceso->_do->id)) {
            $this->eliminaProceso($this->bproceso->_do, true);
            $_SESSION[$this->pref.'_total']--;
        }
    }

    function iniVar($id_persona = null)
    {
        $dproceso=& objeto_tabla('proceso');
        $daccion=& objeto_tabla('accion');

        $db =& $dproceso->getDatabaseConnection();

        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die("Bug: idcaso no deber�a ser null");
        }

        $idp = array();
        $ndp = array();
        $edp = array();
        $indid = -1;
        $tot = PagSegjudicial::extrae_procesos(
            $idcaso, $db, $idp, $ndp,
            $id_persona, $indid, $edp
        );
        $_SESSION[$this->pref.'_total'] = $tot;
        if ($indid >= 0) {
            $_SESSION[$this->pref.'_pag'] = $indid;
        }
        $dproceso->id_caso= $idcaso;
        if ($_SESSION[$this->pref.'_pag'] < 0
            || $_SESSION[$this->pref.'_pag'] >= $tot
        ) {
            $dproceso->id = null;
        } else {
            $dproceso->id = $idp[$_SESSION[$this->pref.'_pag']];
            $dproceso->find();
            $dproceso->fetch();
            $daccion->id_proceso = $dproceso->id;
            $daccion->fetch();
        }

        $this->bproceso =& DB_DataObject_FormBuilder::create(
            $dproceso,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
                'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->baccion=& DB_DataObject_FormBuilder::create(
            $daccion,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
                'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );

        return $db;
    }


    /**
     * Constructora.
     * Ver documentaci�n completa en clase base.
     *
     * @param string $nomForma Nombre
     * @param string $mreq     Mensaje de dato requerido
     *
     * @return void
     */
    function PagSegJudicial($nomForma)
    {
        parent::PagBaseMultiple($nomForma);

        $this->addAction('siguiente', new Siguiente());
        $this->addAction('anterior', new Anterior());
        $this->addAction('eliminaaccionj', new EliminaAccionJ());
        $this->addAction('agregaraccionj', new AgregarAccionJ());
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
        $vv = isset($this->bproceso->_do->id)
            ? $this->bproceso->_do->id : '';
        $this->addElement('hidden', 'id', $vv);
        $this->addElement('');

        $_SESSION['pagJudicial_id'] = $vv;

        $this->bproceso->createSubmit = 0;
        $this->bproceso->useForm($this);
        $this->bproceso->getForm($this);

        $this->baccion->createSubmit = 0;
        $this->baccion->useForm($this);
        $f =& $this->baccion->getForm($this);


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
        $vv = isset($this->bproceso->_do->id) ?
            $this->bproceso->_do->id: '';

        if ($vv != '') {
            $d =& objeto_tabla('proceso');
            $d->get($vv);
            foreach ($d->fb_fieldsToRender as $c) {
                $cq = $this->getElement($c);
                if (!PEAR::isError($cq)) {
                    $cq->setValue($d->$c);
                }
            }
            $cq = $this->getElement('id_tipo');
            $id_tipo = $cq->_elements[0];
            $id_etapa = $cq->_elements[1];
            $id_tipo->setValue($d->id_tipo);
            $id_etapa->setValue($d->id_etapa);
            //die("x");
        }

    }

    function eliminaProceso($dproceso, $elimProc = false)
    {
        assert($dproceso != null);
        assert($dproceso->id != null);
        $db =& $dproceso->getDatabaseConnection();
        $q = "DELETE FROM accion WHERE id_proceso='{$dproceso->id}'";
        $result = hace_consulta($db, $q);
        if ($elimProc) {
            $q = "DELETE FROM proceso WHERE id='{$dproceso->id}'";
            $result = hace_consulta($db, $q);
        }
    }

    /** eliminaDep($db, $idcaso) elimina victimas de la base $db presentados
            en este formulario, que dependen del caso $idcaso */
    static function eliminaDep(&$db, $idcaso)
    {
        assert($db != null);
        assert(isset($idcaso));
        $dproceso =& objeto_tabla('proceso');
        sin_error_pear($dproceso);
        $dproceso->id_caso = $idcaso;
        $dproceso->find();
        while ($dproceso->fetch()) {
            PagSegJudicial::eliminaProceso($dproceso);
            $dproceso->delete();
        }
    }

    /**
    * @param procAc es true si y solo si debe a�adirse Acci�n
    */
    function procesa(&$valores, $procAc = false)
    {
        $valores['id_tipo'] = (int)$valores['tipoetapa'][0];
        $valores['id_etapa'] = (int)$valores['tipoetapa'][1];
        $es_vacio = (
            (!isset($valores['id_tipo'])
            || $valores['id_tipo'] === ''
            || $valores['id_tipo'] == DataObjects_Tipo_proceso::idSinInfo()
            )
            || (!isset($valores['id_etapa'])
                || $valores['id_etapa']==
                DataObjects_Etapa::idSinInfo()
            )
        );

        if ($es_vacio) {
            return true;
        }

        if (!$this->validate() ) {
            return false;
        }

        if (!isset($valores['id']) || $valores['id'] == '') {
            $valores['id'] = null;
            $db = $this->iniVar();
        } else {
            $db = $this->iniVar((int)$valores['id']);
        }
        $dcaso = objeto_tabla('caso');
        if (PEAR::isError($dcaso)) {
            die($dcaso->getMessage());
        }

        $ret = $this->process(array(&$this->bproceso, 'processForm'), false);
        if (PEAR::isError($ret)) {
            die($ret->getMessage());
        }
        if ($procAc) {
            $nacc =& objeto_tabla('accion');
            $nacc->fb_useMutators = true;
            $nacc->id_proceso = $this->bproceso->_do->id;
            $nacc->id_tipo_accion = (int)$valores['id_tipo_accion'];
            $nacc->id_despacho = (int)$valores['id_despacho'];
            $nacc->fecha = arr_a_fecha(var_escapa($valores['fecha'], $db, 20));
            $nacc->numero_radicado = 
                var_escapa($valores['numero_radicado'], $db);
            $nacc->observaciones_accion = 
                var_escapa($valores['observaciones_accion'], $db);
            $nacc->insert();
            $nacc->respondido= isset($valores['respondido'])
                && $valores['respondido'] == 1 ? 't' : 'f';
            $q = "UPDATE accion SET respondido='".$nacc->respondido."' " .
                " WHERE id='".$nacc->id."'";
            hace_consulta($db, $q);
            $procAc = false;
        }

        funcionario_caso($_SESSION['basicos_id']);
        return  $ret;
    }

    function datosBusqueda(&$w, &$t, &$db, $idcaso, &$subcons)
    {

    }

    function handle($action)
    {
//        echo "handle($action)";
//        print_r($this->_actions);
//        die("s");
        parent::handle($action);
    }

    /** Extrae procesos de un caso y retorna su informaci�n en varios
     *  vectores
     *  
     *  @param integer $idcaso  Id. del Caso
     *  @param object  &$db     Conexi�n a BD
     *  @param array   &$idp    Para retornar identificaci�n de procesos
     *
     *  @return integer Cantidad de procesos retornados
     **/
    function extrae_procesos($idcaso, &$db, &$idp)
    {
        $q = "SELECT  id FROM proceso WHERE " .
            "proceso.id_caso='" . (int)$idcaso . "' ORDER BY id";
        $result = hace_consulta($db, $q);
        $row = array();
        $tot = 0;
        while ($result->fetchInto($row)) {
            $idp[] = $row[0];
            $tot++;
        }
        return $tot;
    }

    static function iniCaptura()
    {
        if (isset($_REQUEST['eliminaaccionj'])) {
            $_REQUEST['_qf_segjudicial_eliminaaccionj'] = true;
        }
    }

    static function resConsultaInicio($mostrar, &$renglon, &$rtexto, $tot = 0)
    {
        if ($mostrar == "judicial") {
            echo "<html><head><title>Tabla</title></head>";
            echo "<body>";
            echo "Consulta de " . (int)$tot . " casos. ";
            echo "<p><table border=1 cellspacing=0 cellpadding=5>";
            $renglon = "<tr>";
            $rtexto = "";
        }
    }

    static function resConsultaRegistro(&$db, $mostrar, $idcaso, $campos,
        $conv, &$sal, &$retroalim)
    {
        if ($mostrar == "judicial") {
            ResConsulta::filaTabla(
                $db, $idcaso, $campos, $conv, $sal,
                $retroalim
            );
        }
    }

    static function resConsultaFinal($mostrar)
    {
        if ($mostrar == "judicial") {
            echo "</table>";
        }
    }

    static function consultaWebCreaConsulta(&$db, $mostrar, &$where, &$tablas,
        &$pOrdenar, &$campos)
    {
        if ($mostrar == "judicial") {
            consulta_and_sinap($where, "proceso.id_caso", "caso.id");
            $tablas .= ", proceso";
            $oconv = array('proceso_id', 'proceso_proximafecha');
            $pOrdenar = "fechajudicial";
            $campos['proceso_proximafecha'] = 'Pr�xima fecha';
        }
    }

    static function consultaWebFormaPresentacion($mostrar, $opciones,
        &$forma, &$ae, &$t)
    {
        if (isset($opciones) && in_array(42, $opciones)) {
            $x =&  $forma->createElement(
                'radio', 'mostrar',
                'judicial', 'Tabla Judicial', 'judicial'
            );
            $ae[] =& $x;
            if ($mostrar == 'judicial') {
                $t =& $x;
            }
        }
    }

    static function consultaWebOrden(&$q, $pOrdenar)
    {
        if ($pOrdenar == 'fechajudicial') {
            $q .= ' ORDER by proceso.proximafecha';
        }
    }

    /**
     * Llamada para inicializar variables globales
     */
    static function actGlobales()
    {
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            null, 'Informaci�n Judicial',
            '', null
        );
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            'Informaci�n Judicial', 'Tipos de acciones judiciales',
            'tipo_accion', null
        );
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            'Informaci�n Judicial', 'Tipos de proceso',
            'tipo_proceso', null
        );
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            'Informaci�n Judicial', 'Despacho',
            'despacho', null
        );
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            'Informaci�n Judicial', 'Etapa',
            'etapa', null
        );
    }


}

?>
