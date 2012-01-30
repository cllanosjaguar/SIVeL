<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Base para p�gina simple del multi-formulario para capturar caso
 * (captura_caso.php).
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2005 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: PagBaseSimple.php,v 1.50.2.6 2011/10/13 13:41:06 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Base para p�gina simple del multi-formulario para capturar caso
 */

require_once 'HTML/QuickForm/Page.php';
require_once 'aut.php';
require_once 'misc.php';
require_once $_SESSION['dirsitio'] . '/conf.php';

/**
 * Clase base para subformularios de una s�la p�gina
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 */
abstract class PagBaseSimple extends HTML_QuickForm_Page
{

    /** Titulo que aparecer� en formulario */
    var $titulo = '';

    /** Nombre de una clase DataObject caracter�stica de este formulario */
    var $clase_modelo = 'caso';

    /**
     * Definimos variables para subformularios (a su vez descendientes de
     * DB_DataObject_FormBuilder).
     * Convenci�n sugerida: que comienzan con la letra b
     */
    var $bcaso = null;

    /**
     * Mensaje de campos requeridos
     **/
    var $mreq = '<span style="font-size:80%; color:#ff0000;">*</span>
        <span style = "font-size:80%;"> marca un campo requerido</span>';


    /** Inicializa variables de la clase extrayendo datos de la base.
     * Puede mejorarse manteniendo informaci�n en var. de sesi�n
     * (actualizada con operaciones)
     * para que no tener que consultar la base de datos siempre.
     *
     * @param bool $cargaCaso  decide si se carga o no el caso de la B.D
     * @param bool $retArreglo indica si debe retornar un arreglo con
     * base de datos, objeto dcaso e identificaci�n o s�lo
     * base de datos.
     *
     * @return mixed Puede ser bien conexi�n a base de daotos o bien
     * un arreglo con base, objeto dcaso e identificaci�n de caso (depende
     * del par�metro $retArreglo
     */
    function iniVar($cargaCaso = true, $retArreglo = false)
    {
        $dcaso = objeto_tabla('caso');
        if (PEAR::isError($dcaso)) {
            die($dcaso->getMessage());
        }
        $db =& $dcaso->getDatabaseConnection();

        $idcaso = null;
        if ($cargaCaso) {
            $idcaso =& $_SESSION['basicos_id'];
            if (!isset($idcaso) || $idcaso == null) {
                die("Bug: idcaso no deber�a ser null");
            }
            $dcaso->id = $idcaso;
            if (($e = $dcaso->find()) != 1
                && $idcaso != $GLOBALS['idbus']
            ) {
                die("Se esperaba un s�lo registro, pero se encontraron $e.");
            }
            $dcaso->fetch();
        }

        $this->bcaso =& DB_DataObject_FormBuilder::create(
            $dcaso,
            array(
                'requiredRuleMessage' => $GLOBALS['mreglareq'],
                'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );

        if ($retArreglo) {
            return array($db, $dcaso, $idcaso);
        } else {
            return $db;
        }
    }


    /** Constructora
     *
     * @param string $nomForma es nombre del formulario
     *
     * @return void
     */
    function PagBaseSimple($nomForma)
    {
        $this->HTML_QuickForm_Page($nomForma, 'post', '_self', null);
        $this->setRequiredNote($this->mreq);

        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
        ) {
            $this->addAction('busqueda', new Busqueda());
        }
    }


    /**
     * Agrega elementos particulares del formulario
     *
     * @param handle  &$db    Conexi�n a base de datos
     * @param integer $idcaso Identificaci�n del caso
     *
     * @return void
     */
    abstract function formularioAgrega(&$db, $idcaso);


    /**
    * Establece valores por defecto cuando se requiere para presentar
    * en el formulario.
    *
    * @param handle  &$db    Conexi�n a base de datos
    * @param integer $idcaso Identificaci�n del caso
    *
    * @return void
    */
    abstract function formularioValores(&$db, $idcaso);


    /**
     * Construye elementos del formulario
     *
     * @return object Formulario
     */
    function buildForm()
    {
        $this->_formBuilt = true;
        $this->_submitValues = array();
        $this->_defaultValues = array();

        $cm = "b".$this->clase_modelo;
        if (!isset($cm) || $cm == null || !isset($this->$cm)
            || $this->$cm == null
        ) {
            $db = $this->iniVar();
        } else {
            $db = $this->$cm->_do->getDatabaseConnection();
            if (PEAR::isError($db)) {
                die($cm . " - " . $db->getMessage());
            }
        }
        $idcaso = $_SESSION['basicos_id'];
        $this->controller->creaTabuladores($this, array('class' => 'flat'));


        $comp = isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
            && $idcaso == $GLOBALS['idbus'] ?
            'Consulta' : 'Caso ' . $idcaso;

        $e =& $this->addElement(
            'header', null, '<table width = "100%">' .
            '<th align = "left">' . $this->titulo .
            '</th><th algin = "right">' .
            $comp . "</th></table>"
        );

        $this->formularioAgrega($db, $idcaso);

        $this->controller->creaBotonesEstandar($this);

        $this->setDefaultAction('siguiente');

        // OJO Mejor cambiar valores despu�s de crear formulario, si
        // se hace antes molesta.
        $this->formularioValores($db, $idcaso);
    }


    /**
     * Elimina de la base, datos asociados a un caso y presentados por este
     * formulario.
     *
     * @param handle  &$db    conexi�n a base de datos
     * @param integer $idcaso N�mero de caso
     *
     * @return void
     */
    abstract static function eliminaDep(&$db, $idcaso);


    /**
     * Verifica y salva datos.
     * T�picamente debe validar datos, preprocesar de requerirse,
     * procesar con funci�n process y finalmente registrar evento con funci�n
     * funcionario_caso
     *
     * @param array &$valores Valores enviados por el formulario.
     *
     * @return void
     */
    function procesa(&$valores)
    {
        // Verifica si es vacio

        if (!$this->validate()) {
            return false;
        }
        verifica_sin_CSRF($valores);

        // Reglas de integridad referencial

        $db = $this->iniVar();

        // Preprocesamiento

        // Procesamiento
        $ret = $this->process(array(&$this->bcaso, 'processForm'), false);
        if (PEAR::isError($ret)) {
            die($ret->getMessage());
        }

        // Otros

        funcionario_caso($_SESSION['basicos_id']);
        return  $ret;
    }


    /**
     * Llena una consulta de acuerdo a datos del formulario cuando
     * est� en modo busqueda.
     * <b>SELECT caso.id FROM $t WHERE $w</b>
     *
     * @param string &$w       Condiciones de consulta exterior
     * @param string &$t       Tablas de consulta exterior
     * @param object &$db      Conexi�n a base de datos
     * @param object $idcaso   Identificaci�n de caso
     * @param string &$subcons Consulta interior (si no es vac�a hacer UNION)
     *
     * @return void
     */
    abstract function datosBusqueda(&$w, &$t, &$db, $idcaso, &$subcons);


    /**
     * Llamada cuando se inicia captura de ficha
     *
     * @return void
     */
    static function iniCaptura()
    {
    }


    /**
     * Llamada en cada inicio de una consulta ResConsulta.
     * Hace posible nuevos tipos de consulta.
     *
     * @param string $mostrar  Forma de mostrar consulta
     * @param string &$renglon Llena como iniciar consulta
     * @param string &$rtexto  Llena texto inicial de consula
     * @param array  $tot      Total de casos en consulta
     *
     * @return void
     */
    static function resConsultaInicio($mostrar, &$renglon, &$rtexto, $tot = 0)
    {
    }


    /**
     * Llamada para mostrar un registro en ResConsulta.
     * Hace posible nuevos tipos de consulta.
     *
     * @param object  &$db       Conexi�n a B.D
     * @param string  $mostrar   Forma de mostrar consulta
     * @param int     $idcaso    C�digo de caso
     * @param array   $campos    Campos por mostrar
     * @param array   $conv      Conversiones
     * @param array   $sal       Para conversiones con $conv
     * @param boolean $retroalim Con boton de retroalimentaci�n
     *
     * @return string Fila en HTML
     */
    static function resConsultaRegistro(&$db, $mostrar, $idcaso, $campos,
        $conv, $sal, $retroalim
    ) {
    }

    /**
     * Llamada al final de una consulta ResConsulta.
     * Hace posible nuevos tipos de consulta.
     *
     * @param string $mostrar Forma de mostrar consulta
     *
     * @return void
     */
    static function resConsultaFinal($mostrar)
    {
    }


    /**
     * Llamada cuando se inicia presentaci�n en formato de tabla.
     * Da oportunidad por ejemplo de inicializar variables.
     *
     * @param string $cc Campo que se muestra
     *
     * @return void
     */
    static function resConsultaInicioTabla($cc)
    {
    }

    /**
     * Llamada desde la funci�n que muestra cada fila de la tabla en
     * ResConsulta.
     * Hace posible modificar la tabla.
     *
     * @param object &$db    Base de datos
     * @param string $cc     Campo que se procesa
     * @param int    $idcaso N�mero de caso
     *
     * @return Cadena por presentar
     */
    static function resConsultaFilaTabla(&$db, $cc, $idcaso)
    {
    }

    /**
     * Llamada desde consulta web en formato de tabla al terminar tabla.
     *
     * @param string $cc Campo que se procesa
     *
     * @return Cadena por presentar
     */
    static function resConsultaFinaltablaHtml($cc)
    {
    }

    /**
     * Llamada desde consulta web durante construcci�n de formulario para
     * dar la posibilidad de a�adir elementos.
     *
     * @param object &$db   Conexi�n a B.D
     * @param object &$form Formulario
     *
     * @return Cadena por presentar
     */

    static function consultaWebFiltro(&$db, &$form)
    {
    }


    /**
     * Llamada desde consulta_web para completar consulta SQL en caso
     *
     * @param object &$db       Conexi�n a B.D
     * @param string $mostrar   Forma de mostrar consulta
     * @param string &$where    Consulta SQL por completar
     * @param string &$tablas   Tablas incluidas en consulta
     * @param array  &$pOrdenar Forma de ordenamiento
     * @param array  &$campos   Campos por mostrar
     *
     * @return void
     */
    static function consultaWebCreaConsulta(&$db, $mostrar, &$where,
        &$tablas, &$pOrdenar, &$campos
    ) {
    }


    /**
     * Llamada desde consulta_web al generar formulario en porci�n
     * `Forma de presentaci�n'
     *
     * @param string $mostrar  Forma de mostrar consulta
     * @param array  $opciones Opciones de menu del usuario
     * @param object &$forma   Formulario
     * @param array  &$ae      Grupo de elementos que conforman Forma de pres.
     * @param array  &$t       Si est� marcado lo pone en el elemento creado
     *
     * @return void
     */
    static function consultaWebFormaPresentacion($mostrar, $opciones,
        &$forma, &$ae, &$t
    ) {
    }


    /**
     * Llamada desde consulta_web para generar formulario en porci�n
     * 'Detalles de la presentaci�n'
     *
     * @param string $mostrar  Forma de mostrar consulta
     * @param array  $opciones Opciones de menu del usuario
     * @param object &$forma   Formulario
     * @param array  &$opch    Grupo de elementos que conforman Forma de pres.
     *
     * @return void
     */
    static function consultaWebDetalle($mostrar, $opciones,
        &$forma, &$opch
    ) {
    }


    /**
     * Llamada desde consulta_web para completar consulta poniendo una
     * pol�tica de ordenamiento
     *
     * @param object &$q       Consulta por modificar
     * @param string $pOrdenar Criterio de ordenamiento
     *
     * @return void
     */
    static function consultaWebOrden(&$q, $pOrdenar)
    {
    }


    /**
     * Llamada para completar registro por mostrar en Reporte General.
     *
     * @param object &$db    Conexi�n a B.D
     * @param array  $campos Campos por mostrar
     * @param int    $idcaso C�digo de caso
     *
     * @return void
     */
    static function reporteGeneralRegistroHtml(&$db, $campos, $idcaso)
    {
    }


    /**
     * Llamada para completar registro por mostrar en Reporte Revista.
     *
     * @param object &$db    Conexi�n a B.D
     * @param array  $campos Campos por mostrar
     * @param int    $idcaso C�digo de caso
     *
     * @return void
     */
    static function reporteRevistaRegistroHtml(&$db, $campos, $idcaso)
    {
    }


    /**
     * Llamada desde formulario de estad�sticas individuales para
     * dar la posibilidad de a�adir elementos.
     *
     * @param object &$db   Conexi�n a B.D
     * @param object &$form Formulario
     *
     * @return Cadena por presentar
     */
    static function estadisticasIndFiltro(&$db, &$form)
    {
    }

    /**
     * Llamada desde estadisticas.php para completar primera consulta SQL
     * que genera estad�sticas
     *
     * @param object &$db     Conexi�n a B.D
     * @param string &$where  Consulta SQL que se completa
     * @param string &$tablas Tablas incluidas en consulta
     *
     * @return void Modifica $tablas y $where
     */
    static function estadisticasIndCreaConsulta(&$db, &$where, &$tablas) 
    {
    }

    /**
     * Llamada para inicializar variables globales como cw_ncampos
     *
     * @return void
     */
    static function actGlobales()
    {
    }

    /**
     * Llamada para crear encabezado en Javascript
     *
     * @param string &$js colchon de funciones en javascript 
     *
     * @return void
     */
    static function encJavascript(&$js)
    {
    }

    /**
     * Importa de un relato SINCODH lo relacionado con esta pesta�a,
     * creando registros en la base de datos para el caso $idcaso
     *
     * @param object &$db    Conexi�n a base de datos
     * @param object $r      Relato en XML
     * @param int    $idcaso N�mero de caso que se inserta
     * @param string &$obs   Colchon para agregar notas de conversion
     *
     * @return void
     * @see PagBaseSimple
     */
    static function importaRelato(&$db, $r, $idcaso, &$obs)
    {
    }

}

?>
