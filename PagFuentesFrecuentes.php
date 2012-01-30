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
 * @version   CVS: $Id: PagFuentesFrecuentes.php,v 1.71.2.3 2011/10/13 09:57:49 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Pesta�a Fuentes Frecuentes del multi-formulario capturar caso
 */

require_once 'PagBaseMultiple.php';
require_once 'DataObjects/Prensa.php';

/**
 * P�gina fuentes frecuentes.
 * Ver documentaci�n de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      PagBaseMultiple
 */
class PagFuentesFrecuentes extends PagBaseMultiple
{

    /** Fuente frecuente asociada al caso, que se est� consultando */
    var $bescrito_caso;

    var $titulo = 'Fuentes Frecuentes';

    var $tcorto = 'Fuente';

    var $pref = "ff";

    var $nuevaCopia = false;

    var $clase_modelo = 'escrito_caso';

    /**
     * Pone en null variables asociadas a tablas de la pesta�a.
     *
     * @return null
     */
    function nullVar()
    {
        $this->bescrito_caso = null;
    }

    /**
     * Retorna una identificaci�n del registro actual.
     *
     * @return string Identifaci�n
     */
    function copiaId()
    {
        $r = $this->bescrito_caso->_do->id_caso.":" .
            $this->bescrito_caso->_do->id_prensa.":" .
            $this->bescrito_caso->_do->fecha;
        return  $r;
    }


     /**
     * Elimina de base de datos el registro actual.
     *
     * @param array &$valores Valores enviados por formulario.
     *
     * @return null
     */
    function elimina(&$valores)
    {
        $this->iniVar();
        if ($this->bescrito_caso->_do->id_prensa != null) {
            $this->bescrito_caso->_do->delete();
            $_SESSION['ff_total']--;
        }
    }

    /**
     * Inicializa variables y datos de la pesta�a.
     * Ver documentaci�n completa en clase base.
     *
     * @return handle Conexi�n a base de datos
     */
    function iniVar()
    {
        $do =& objeto_tabla('escrito_caso');
        $db =& $do->getDatabaseConnection();
        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die("Bug: idcaso no deber�a ser null");
        }
        $do->id_caso = $idcaso;
        $result = hace_consulta(
            $db, "SELECT id_prensa " .
            " FROM escrito_caso, prensa " .
            " WHERE id_caso='$idcaso' AND id_prensa=id " .
            " ORDER BY nombre;"
        );
        $row = array();
        $idp = array();
        $tot = 0;
        while ($result->fetchInto($row)) {
            $idp[] = $row[0];
            $tot++;
        }
        $_SESSION['ff_total'] = $tot;
        if ($_SESSION['ff_pag'] < 0 || $_SESSION['ff_pag'] >= $tot) {
            $do->id_prensa = null;
        } else {
            $do->id_prensa = $idp[$_SESSION['ff_pag']];
            $do->find();
            $do->fetch();
        }

        $this->bescrito_caso =& DB_DataObject_FormBuilder::create(
            $do,
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
     *
     * @return void
     */
    function PagFuentesFrecuentes($nomForma)
    {
        parent::PagBaseMultiple($nomForma);

        $this->addAction('siguiente', new Siguiente());
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
        $GLOBALS['fechaPuedeSerVacia'] = isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda';

        $this->bescrito_caso->createSubmit = 0;
        $this->bescrito_caso->useForm($this);
        $this->bescrito_caso->getForm();

        $this->registerRule(
            'frecuenteposterior', 'function', 'frecposterior',
            'PagFuentesFrecuentes'
        );
        $this->addRule(
            'fecha',
            'La fecha de la fuente debe ser posterior a la del caso',
            'frecuenteposterior', null, 'client'
        );

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

        if (isset($_SESSION['recuperaErrorValida'])) {
            establece_valores_form(
                $this,
                $this->bescrito_caso->_do->fb_fieldsToRender,
                $_SESSION['recuperaErrorValida']
            );
            unset($_SESSION['recuperaErrorValida']);
        } else {
            foreach ($this->bescrito_caso->_do->fb_fieldsToRender as $c) {
                $cq = $this->getElement($c);
                if (isset($this->bescrito_caso->_do->$c)) {
                    $cq->setValue($this->bescrito_caso->_do->$c);
                }
            }
        }
        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
        ) {
            $f = $this->getElement('fecha');
            $da = $f->getValue();
            $y = date('Y');
            $m = date('m');
            $d = date('d');
            if ($da['Y'][0] == ($GLOBALS['anio_min'] - 1)
                || ($y == $da['Y'][0] && $d == $da['d'][0] && $m == $da['m'][0])
            ) {
                $f->setValue(
                    array('d' => array('0' => ''),
                    'm' => array('0' => ''),
                    'Y' => array('0' => '')
                    )
                );
            }
        }


        if (isset($_SESSION['nuevo_copia_id'])
            && strstr($_SESSION['nuevo_copia_id'], ":") != false
        ) {
            list($idc, $idp, $fecha)
                = explode(':', $_SESSION['nuevo_copia_id']);
            unset($_SESSION['nuevo_copia_id']);
            $d =& objeto_tabla('escrito_caso');
            $d->id_caso = $idc;
            $d->id_prensa = $idp;
            $d->fecha = $fecha;
            $d->find();
            $d->fetch();
            foreach ($d->fb_fieldsToRender as $c) {
                $cq = $this->getElement($c);
                $cq->setValue($d->$c);
            }
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
        assert($db != null);
        assert(isset($idcaso));
        hace_consulta($db, "DELETE FROM escrito_caso WHERE id_caso='$idcaso'");
    }

    /**
     * Procesa valores del formulario enviados por el usuario.
     * Ver documentaci�n completa en clase base.
     *
     * @param handle &$valores Valores ingresados por usuario
     *
     * @return bool Verdadero si y solo si puede completarlo con �xito
     * @see PagBaseSimple
     */
    function procesa(&$valores)
    {
        $es_vacio = ($valores['id_prensa'] == null
                || $valores['id_prensa'] == ''
        )
            && ($valores['ubicacion'] == null || $valores['ubicacion'] == '')
            && ($valores['clasificacion'] == null
                || $valores['clasificacion'] == ''
            )
            && ($valores['ubicacion_fisica'] == null
                || $valores['ubicacion_fisica'] == ''
                )
        ;

        if ($es_vacio) {
            return true;
        }
        if (!$this->validate()) {
            return false;
        }
        verifica_sin_CSRF($valores);

        $db = $this->iniVar();
        $do =& objeto_tabla('caso');
        $do->id = $this->bescrito_caso->_do->id_caso;
        $do->find();
        $do->fetch();
        $df = call_user_func(
            $this->bescrito_caso->dateToDatabaseCallback,
            var_escapa($valores['fecha'], $db, 20)
        );
        $nobusca = !isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda';
        /* No funcionan reglas de validaci�n de QuickForm
           porque no est� construido el formulario cuando pasa
           por esta funci�n.  $this->validate encuentra que
           $this->_rules es vacio */
        if ($nobusca && strtotime($df) < strtotime($do->fecha)) {
            error_valida(
                'Fecha de fuente no puede ser anterior a la del caso',
                $valores
            );
            return false;
        }

        if ($this->bescrito_caso->_do->id_prensa != null) {
            $this->bescrito_caso->_do->delete();
            $_SESSION['ff_total']--;
        }
        $this->bescrito_caso->forceQueryType(
            DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEINSERT
        );

        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
            && isset($valores['id_caso'])
            && $valores['id_caso'] == $GLOBALS['idbus']
        ) {
            if ($df == "0000-00-00") {
                $valores['fecha']['d'] = 1;
                $valores['fecha']['m'] = 1;
                $valores['fecha']['Y'] = $GLOBALS['anio_min'] - 1;
            }
            if ($valores['id_prensa'] == '') {
                $valores['id_prensa'] = DataObjects_Prensa::id_sinInfo();
            }
        }

        $ret = $this->process(
            array(&$this->bescrito_caso, 'processForm'),
            false
        );
        $_SESSION['ff_total']++;

        funcionario_caso($_SESSION['basicos_id']);
        return  $ret;
    }



    /**
     * Prepara consulta SQL para buscar datos de este formulario.
     * Ver documentaci�n completa en clase base.
     *
     * @param string &$w       Consulta que se construye
     * @param string &$t       Tablas
     * @param string &$db      Conexi�n a base de datos
     * @param object $idcaso   Identificaci�n de caso
     * @param string &$subcons Subconsulta
     *
     * @return void
     * @see PagBaseSimple
     */
    function datosBusqueda(&$w, &$t, &$db, $idcaso, &$subcons)
    {
        prepara_consulta_gen(
            $w, $t, $idcaso,
            'escrito_caso', 'Prensa', 'id_prensa', false
        );
        // echo "OJO w=$w";
    }

    /**
     * Busca una fuente frecuente por nombre y la inserta en un caso
     * con los datos que esta funci�n recibe.
     *
     * @param object &$db    Conexi�n a base de datos
     * @param intger $idcaso N�mero de caso al que se a�ade fuente
     * @param string $nomf   Nombre de la fuente
     * @param string $fecha  Fecha de fuente
     * @param string $ubif   Ubicaci�n f�sica
     * @param string $ubi    Ubicaci�n
     * @param string $cla    Clasificaci�n
     * @param string &$obs   Colchon para agregar notas de conversion
     *
     * @return boolean true sii encuentra y puede insertar
     */
    static function busca_inserta(&$db, $idcaso, $nomf, $fecha, 
        $ubif, $ubi, $cla, &$obs
    ) {
        $rp = hace_consulta(
            $db, "SELECT id FROM prensa WHERE " .
            "nombre ILIKE '$nomf'"
        );
        $rows = array();
        $nr = $rp->numRows();
        if ($rp->fetchInto($row)) {
            $idprensa = $row[0];
            if ($rp->fetchInto($row)) {
                repObs(
                    "Hay $nr fuentes frecuentes con nombre como " .
                    $fuente->nombre_fuente .
                    ", escogido el primero\n", $obs
                );
            }
            if (!empty($fecha)) {
                $escritocaso = objeto_tabla('escrito_caso');
                $escritocaso->id_caso = $idcaso;
                $escritocaso->id_prensa = $idprensa;
                $escritocaso->fecha = $fecha;
                if (!empty($ubif)) {
                    $escritocaso->ubicacion_fisica = $ubif;
                }
                $escritocaso->ubicacion = $ubi;
                $escritocaso->clasificacion = $cla;
                $escritocaso->insert();
                return true;
            }
        }

        return false;
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
        foreach ($r->fuente as $fuente) {
            $idprensa = null;
            $nomf = utf8_decode($fuente->nombre_fuente);
            if (empty($fuente->fecha_fuente)) {
                repObs(
                    "No se incluy� fuente sin fecha: " .
                    $fuente->asXML(), $obs
                );
            } else if (empty($fuente->nombre_fuente)) {
                repObs(
                    "No se incluy� fuente sin nombre: " .
                    $fuente->asXML(), $obs
                );
            } else {
                $fecha = conv_fecha($fuente->fecha_fuente, $obs);
                busca_inserta(
                    $db, $idcaso, $nomf, $fecha, 
                    utf8_decode((string)$fuente->ubicacion_fuente),
                    dato_en_obs($fuente, 'ubicacion'),
                    dato_en_obs($fuente, 'clasificacion'),
                    $ubif, $ubi, $cla, $obs
                );
            }
        }
    }

}

?>
