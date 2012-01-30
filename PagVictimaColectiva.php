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
 * @version   CVS: $Id: PagVictimaColectiva.php,v 1.93.2.3 2011/10/13 09:57:49 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Pesta�a V�ctima Colectiva de la ficha de captura de caso
 */

require_once 'PagBaseMultiple.php';
require_once 'ResConsulta.php';

/**
 * V�ctima Colectiva.
 * Ver documentaci�n de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      PagBaseMultiple
 */
class PagVictimaColectiva extends PagBaseMultiple
{

    /** Grupo de personas */
    var $bgrupoper;

    /** V�ctima colectiva (independiente de caso) */
    var $bvictima_colectiva ;

    /** Antecedentes */
    var $bantecedente_comunidad;

    /** Rangos de edad */
    var $brango_edad_comunidad;

    /** Sectores sociales */
    var $bsector_social_comunidad;

    /** V�nculos con estado */
    var $bvinculo_estado_comunidad;

    /** Filiaciones */
    var $bfiliacion_comunidad;

    /** Profesiones */
    var $bprofesion_comunidad;

    /** Organizaciones */
    var $borganizacion_comunidad;

    /** Titulo completo  */
    var $titulo = 'V�ctimas Colectivas';

    /** Titulo corto */
    var $tcorto = 'Vic. colectiva';

    /** Prefijo para variables de sesi�n */
    var $pref = "fvc";

    /** Nueva Copia */
    var $nuevaCopia = false;

    /** Clase modelo para pesta�a */
    var $clase_modelo = 'victima_colectiva';

    /**
     * Pone en null variables asociadas a tablas de la pesta�a.
     *
     * @return null
     */
    function nullVar()
    {
        $this->bgrupoper= null;
        $this->bvictima_colectiva = null;
        $this->bantecedente_comunidad = null;
        $this->brango_edad_comunidad = null;
        $this->bsector_social_comunidad = null;
        $this->bvinculo_estado_comunidad = null;
        $this->bfiliacion_comunidad = null;
        $this->bprofesion_comunidad = null;
        $this->borganizacion_comunidad = null;
    }

    /**
     * Retorna una identificaci�n del registro actual.
     *
     * @return string Identifaci�n
     */
    function copiaId()
    {
        return $this->bvictima_colectiva->_do->id_grupoper;
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
        if (isset($this->bvictima_colectiva->_do->id_grupoper)) {
            $this->eliminaVic($this->bvictima_colectiva->_do, true);
            $_SESSION['fvc_total']--;
        }
    }

    var $tablasrel = array(
        'grupoper',
        'victima_colectiva',
        'antecedente_comunidad',
        'rango_edad_comunidad',
        'sector_social_comunidad',
        'vinculo_estado_comunidad',
        'filiacion_comunidad',
        'profesion_comunidad',
        'organizacion_comunidad',
    );



    /**
     * Inicializa variables.
     *
     * @param integer $id_grupoper Id  de grupo de personas
     *
     * @return handle  Conexi�n a base de datos
     */
    function iniVar($id_grupoper = null)
    {

        $dgrupoper =& objeto_tabla('grupoper');
        $dvictima_colectiva =& objeto_tabla('victima_colectiva');
        $dantecedente_comunidad =&
            objeto_tabla('antecedente_comunidad', $this);
        $drango_edad_comunidad =&
            objeto_tabla('rango_edad_comunidad');
        $dsector_social_comunidad =&
            objeto_tabla('sector_social_comunidad');
        $dvinculo_estado_comunidad =&
            objeto_tabla('vinculo_estado_comunidad');
        $dfiliacion_comunidad =& objeto_tabla('filiacion_comunidad');
        $dprofesion_comunidad =& objeto_tabla('profesion_comunidad');
        $dorganizacion_comunidad =&
            objeto_tabla('organizacion_comunidad');
        $db =& $dvictima_colectiva->getDatabaseConnection();

        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die("Bug: idcaso no deber�a ser null");
        }

        $idp = $ndp = $cdp = array();
        $indid = -1;
        $tot = 0;
        ResConsulta::extraeColectivas(
            $idcaso, $db, $idp, $ndp, $cdp,
            $id_grupoper, $indid, $tot
        );
        $_SESSION['fvc_total'] = $tot;
        if ($indid >= 0) {
            $_SESSION['fvc_pag'] = $indid;
        }
        $dvictima_colectiva->id_caso= $idcaso;
        if ($_SESSION['fvc_pag'] < 0 || $_SESSION['fvc_pag'] >= $tot) {
            $dvictima_colectiva->id_grupoper = null;
        } else {
            $dvictima_colectiva->id_grupoper = $idp[$_SESSION['fvc_pag']];
            $dvictima_colectiva->find();
            $dvictima_colectiva->fetch();
            $dgrupoper->id = $idp[$_SESSION['fvc_pag']];
            $dgrupoper->find();
            $dgrupoper->fetch();
        }
        $idgrupoper = $dvictima_colectiva->id_grupoper;
        $dantecedente_comunidad->id_grupoper = $idgrupoper;
        $dantecedente_comunidad->id_caso= $idcaso;
        $drango_edad_comunidad->id_grupoper= $idgrupoper;
        $drango_edad_comunidad->id_caso= $idcaso;
        $dsector_social_comunidad->id_grupoper = $idgrupoper;
        $dsector_social_comunidad->id_caso = $idcaso;
        $dvinculo_estado_comunidad->id_grupoper = $idgrupoper;
        $dvinculo_estado_comunidad->id_caso = $idcaso;
        $dfiliacion_comunidad->id_grupoper = $idgrupoper;
        $dfiliacion_comunidad->id_caso= $idcaso;
        $dprofesion_comunidad->id_grupoper= $idgrupoper;
        $dprofesion_comunidad->id_caso = $idcaso;
        $dorganizacion_comunidad->id_grupoper = $idgrupoper;
        $dorganizacion_comunidad->id_caso = $idcaso;

        $this->bgrupoper =& DB_DataObject_FormBuilder::create(
            $dgrupoper,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bvictima_colectiva  =& DB_DataObject_FormBuilder::create(
            $dvictima_colectiva,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bvictima_colectiva->useMutators = true;
        $this->bantecedente_comunidad =& DB_DataObject_FormBuilder::create(
            $dantecedente_comunidad,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->brango_edad_comunidad =& DB_DataObject_FormBuilder::create(
            $drango_edad_comunidad,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bsector_social_comunidad =& DB_DataObject_FormBuilder::create(
            $dsector_social_comunidad,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bvinculo_estado_comunidad =& DB_DataObject_FormBuilder::create(
            $dvinculo_estado_comunidad,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bfiliacion_comunidad =& DB_DataObject_FormBuilder::create(
            $dfiliacion_comunidad,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bprofesion_comunidad =& DB_DataObject_FormBuilder::create(
            $dprofesion_comunidad,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->borganizacion_comunidad =& DB_DataObject_FormBuilder::create(
            $dorganizacion_comunidad,
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
     * @see    PagBaseMultiple
     */
    function PagVictimaColectiva($nomForma)
    {
        parent::PagBaseMultiple($nomForma);
        $this->addAction('siguiente', new Siguiente());
        $this->addAction('anterior', new Anterior());

        $this->titulo = $GLOBALS['etiqueta']['victimas_colectivas'];
    }


    /**
     * Agrega elementos al formulario.
     * Ver documentaci�n completa en clase base.
     *
     * @param handle &$db    Conexi�n a base de datos
     * @param string $idcaso Id del caso
     *
     * @return void
     * @see    PagBaseSimple
     */
    function formularioAgrega(&$db, $idcaso)
    {
        $e = $this->getElement(null);
        $vv= isset($this->bvictima_colectiva->_do->id_grupoper) ?
            $this->bvictima_colectiva->_do->id_grupoper : '';
        //$this->addElement('hidden', 'id_grupoper', $vv);

        $this->bgrupoper->createSubmit = 0;
        $this->bgrupoper->useForm($this);
        $this->bgrupoper->getForm();

        $this->bvictima_colectiva->createSubmit = 0;
        $this->bvictima_colectiva->useForm($this);
        $this->bvictima_colectiva->getForm();

        $this->bantecedente_comunidad->createSubmit = 0;
        $this->bantecedente_comunidad->useForm($this);
        $this->bantecedente_comunidad->getForm();

        $this->brango_edad_comunidad->createSubmit = 0;
        $this->brango_edad_comunidad->useForm($this);
        $this->brango_edad_comunidad->getForm();

        $this->bsector_social_comunidad->createSubmit = 0;
        $this->bsector_social_comunidad->useForm($this);
        $this->bsector_social_comunidad->getForm();

        $this->bvinculo_estado_comunidad->createSubmit = 0;
        $this->bvinculo_estado_comunidad->useForm($this);
        $this->bvinculo_estado_comunidad->getForm();

        $this->bfiliacion_comunidad->createSubmit = 0;
        $this->bfiliacion_comunidad->useForm($this);
        $this->bfiliacion_comunidad->getForm();

        $e =& $this->getElement('id_filiacion');
        $this->bprofesion_comunidad->createSubmit = 0;
        $this->bprofesion_comunidad->useForm($this);
        $this->bprofesion_comunidad->getForm();

        $this->borganizacion_comunidad->createSubmit = 0;
        $this->borganizacion_comunidad->useForm($this);
        $this->borganizacion_comunidad->getForm();

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
     * @see    PagBaseSimple
     */
    function formularioValores(&$db, $idcaso)
    {
        $campos = array_merge(
            array('anotaciones'),
            $this->bvictima_colectiva->_do->fb_fieldsToRender,
            array(
            'id_antecedente', 'id_rango',
            'id_sector', 'id_vinculo_estado', 'id_filiacion',
            'id_profesion', 'id_organizacion'
            )
        );

        $vv= isset($this->bvictima_colectiva->_do->id_grupoper) ?
            $this->bvictima_colectiva->_do->id_grupoper : '';
        $v = array();
        if (isset($_SESSION['recuperaErrorValida'])) {
            $v = $_SESSION['recuperaErrorValida'];
        } else if (isset($_SESSION['nuevo_copia_id'])
            && $_SESSION['nuevo_copia_id'] != ''
        ) {
            $id = $_SESSION['nuevo_copia_id'];
            $_SESSION['nuevo_copia_id'] = '';
            unset($_SESSION['nuevo_copia_id']);

            $dg =& objeto_tabla('grupoper');
            $dg->id = $id;
            $dg->find(1);
            $cq = $this->getElement('gid');
            $p = $cq->_elements[1];
            $p->setValue($dg->nombre);

            $d =& objeto_tabla('victima_colectiva');
            $d->id_grupoper = $id;
            $d->id_caso = $_SESSION['basicos_id'];
            $d->find();
            $d->fetch(1);
            foreach ($d->fb_fieldsToRender as $c) {
                $v[$c] = $d->$c;
            }
            $r = array('antecedente_comunidad' => 'id_antecedente',
                'rango_edad_comunidad' =>  'id_rango',
                'sector_social_comunidad' =>  'id_sector',
                'vinculo_estado_comunidad' =>  'id_vinculo_estado',
                'filiacion_comunidad' =>  'id_filiacion',
                'profesion_comunidad' =>  'id_profesion',
                'organizacion_comunidad' =>  'id_organizacion'
            );
            foreach ($r as $nt => $ca) {
                $valel = array();
                $d =& objeto_tabla($nt);
                $d->id_grupoper = $id;
                $d->find();
                while ($d->fetch()) {
                    $valel[] = $d->$ca;
                }
                //   $el->setValue($valel);
                $v[$ca] = $valel;
            }

            $this->removeElement('id_grupoper');
        } else if ($vv != '') {
            $valsca = array();
            $valscre = array();
            $valscss = array();
            $valscve = array();
            $valscfc = array();
            $valscpro = array();
            $valscoc = array();

            $this->bantecedente_comunidad->_do->find();
            while ($this->bantecedente_comunidad->_do->fetch()) {
                $valsca[] = $this->bantecedente_comunidad->_do->id_antecedente;
            }

            $n = $this->brango_edad_comunidad->_do->find();
            while ($this->brango_edad_comunidad->_do->fetch()) {
                $valscre[] = $this->brango_edad_comunidad->_do->id_rango;
            }

            $this->bsector_social_comunidad->_do->find();
            while ($this->bsector_social_comunidad->_do->fetch()) {
                $valscss[] = $this->bsector_social_comunidad->_do->id_sector;
            }

            $this->bvinculo_estado_comunidad->_do->find();
            while ($this->bvinculo_estado_comunidad->_do->fetch()) {
                $valscve[]
                    = $this->bvinculo_estado_comunidad->_do->id_vinculo_estado;
            }

            $this->bfiliacion_comunidad->_do->find();
            while ($this->bfiliacion_comunidad->_do->fetch()) {
                $valscfc[] = $this->bfiliacion_comunidad->_do->id_filiacion;
            }

            $this->bprofesion_comunidad->_do->find();
            while ($this->bprofesion_comunidad->_do->fetch()) {
                $valscpro[] = $this->bprofesion_comunidad->_do->id_profesion;
            }

            $this->borganizacion_comunidad->_do->find();
            while ($this->borganizacion_comunidad->_do->fetch()) {
                $valscoc[] = $this->borganizacion_comunidad->_do->id_organizacion;
            }
            $v['id_antecedente'] = $valsca;
            $v['id_rango'] = $valscre;
            $v['id_sector'] = $valscss;
            $v['id_vinculo_estado'] = $valscve;
            $v['id_filiacion'] = $valscfc;
            $v['id_profesion'] = $valscpro;
            $v['id_organizacion'] = $valscoc;
            $v['id_organizacion_armada']
                = $this->bvictima_colectiva->_do->id_organizacion_armada;
            $v['personas_aprox']
                = isset($this->bvictima_colectiva->_do->personas_aprox)
                && $this->bvictima_colectiva->_do->personas_aprox != 'null' ?
                $this->bvictima_colectiva->_do->personas_aprox :
                '';
        }
        establece_valores_form($this, $campos, $v);

        if (isset($_SESSION['recuperaErrorValida'])) {
            unset($_SESSION['recuperaErrorValida']);
        }

    }


    /**
     * Elimina una v�ctima
     *
     * @param object $dvcolectiva Objeto con vic. col
     * @param bool   $este        Verdadero sii se elimina objeto dvcolectiva
     *
     * @return void
     */
    function eliminaVic($dvcolectiva, $este)
    {
        if ($dvcolectiva->id_grupoper != null) {
            $db =& $dvcolectiva->getDatabaseConnection();
            $idgrupoper = $dvcolectiva->id_grupoper;
            $idcaso = $dvcolectiva->id_caso;
            hace_consulta(
                $db, "DELETE FROM antecedente_comunidad " .
                " WHERE id_caso=$idcaso " .
                " AND id_grupoper='$idgrupoper'"
            );
            hace_consulta(
                $db, "DELETE FROM rango_edad_comunidad " .
                " WHERE id_caso=$idcaso " .
                " AND id_grupoper='$idgrupoper'"
            );
            hace_consulta(
                $db, "DELETE FROM sector_social_comunidad " .
                " WHERE id_caso=$idcaso " .
                " AND id_grupoper='$idgrupoper'"
            );
            hace_consulta(
                $db, "DELETE " .
                " FROM vinculo_estado_comunidad " .
                " WHERE id_caso=$idcaso " .
                " AND id_grupoper='$idgrupoper'"
            );
            hace_consulta(
                $db, "DELETE FROM filiacion_comunidad " .
                " WHERE id_caso=$idcaso " .
                " AND id_grupoper='$idgrupoper'"
            );
            hace_consulta(
                $db, "DELETE FROM profesion_comunidad " .
                " WHERE id_caso=$idcaso " .
                " AND id_grupoper='$idgrupoper'"
            );
            hace_consulta(
                $db, "DELETE FROM organizacion_comunidad " .
                " WHERE id_caso=$idcaso " .
                " AND id_grupoper='$idgrupoper'"
            );
            if ($este) {
                $dvcolectiva->delete();
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
     * @see    PagBaseSimple
     */
    static function eliminaDep(&$db, $idcaso)
    {
        assert($db != null);
        assert(isset($idcaso));
        $dvcolectiva =& objeto_tabla('victima_colectiva');
        $dvcolectiva->id_caso = $idcaso;
        $dvcolectiva->find();
        $cp = array();
        while ($dvcolectiva->fetch()) {
            $cp[] = $dvcolectiva->id_grupoper;
        }
        foreach ($cp as $idg) {
            $dvcolectiva =& objeto_tabla('victima_colectiva');
            $dvcolectiva->id_caso = $idcaso;
            $dvcolectiva->id_grupoper= $idg;
            $dvcolectiva->find();
            $dvcolectiva->fetch();
            PagVictimaColectiva::eliminaVic($dvcolectiva, true);
        }
    }


    /**
     * Procesa valores del formulario enviados por el usuario.
     * Ver documentaci�n completa en clase base.
     *
     * @param handle &$valores Valores ingresados por usuario
     *
     * @return bool   Verdadero si y solo si puede completarlo con �xito
     * @see    PagBaseSimple
     */
    function procesa(&$valores)
    {
        $es_vacio = (!isset($valores['nombre']) || $valores['nombre'] == '')
            && (!isset($valores['anotacion']) || $valores['anotacion'] == '')
            && (!isset($valores['personas_aprox'])
            || $valores['personas_aprox'] == ''
            )
            && (!isset($valores['id_antecedente'])
            || $valores['id_antecedente'] == array()
            )
            && (!isset($valores['id_rango'])
            || $valores['id_rango'] == array()
            )
            && (!isset($valores['id_sector'])
            || $valores['id_sector'] == array()
            )
            && (!isset($valores['id_vinculo_estado'])
            || $valores['id_vinculo_estado'] == array()
            )
            && (!isset($valores['id_filiacion'])
            || $valores['id_filiacion'] == array()
            )
            && (!isset($valores['id_profesion'])
            || $valores['id_profesion'] == array()
            )
            && (!isset($valores['id_organizacion'])
            || $valores['id_organizacion'] == array()
            )
        ;

        if ($es_vacio) {
            return true;
        }
        if (!$this->validate() ) {
            return false;
        }

        verifica_sin_CSRF($valores);

        $nobusca = !isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda';
        if ($nobusca
            && (!isset($valores['nombre']) || trim($valores['nombre'])=='')
        ) {
            error_valida('Falta nombre de v�ctima colectiva', $valores);
            return false;
        }

        if (!isset($valores['id_grupoper']) || $valores['id_grupoper'] == '') {
            $valores['id_grupoper'] = null;
            $db = $this->iniVar();
        } else {
            $db = $this->iniVar((int)$valores['id_grupoper']);
        }


        $idcaso = $_SESSION['basicos_id'];
        $ret = $this->process(
            array(&$this->bgrupoper, 'processForm'),
            false
        );
        if (PEAR::isError($ret)) {
            die($ret->getMessage());
        }
        $idgrupoper = $this->bgrupoper->_do->id;
        $nuevo = $this->bvictima_colectiva->_do->id_grupoper == null
            || $this->bvictima_colectiva->_do->id_grupoper != $idgrupoper;

        $this->bvictima_colectiva->_do->id_grupoper = $idgrupoper;
        $valores['id_grupoper'] = $idgrupoper;
        if ($nuevo) {
            $this->bvictima_colectiva->forceQueryType(
                DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEINSERT
            );
        }
        $ret = $this->process(
            array(&$this->bvictima_colectiva,
            'processForm'
            ), false
        );
        if (PEAR::isError($ret)) {
            die($ret->getMessage());
        }

        if ($nuevo) {
            $_SESSION['fvc_total']++;
        } else {
            $this->eliminaVic($this->bvictima_colectiva->_do, false);
        }

        if (isset($valores['id_antecedente'])) {
            foreach (var_escapa($valores['id_antecedente']) as $k => $v) {
                $this->bantecedente_comunidad->_do->id_grupoper = $idgrupoper;
                $this->bantecedente_comunidad->_do->id_caso = $idcaso;
                $this->bantecedente_comunidad->_do->id_antecedente
                    = (int)var_escapa($v, $db);
                $this->bantecedente_comunidad->_do->insert();
            }
        }


        if (isset($valores['id_rango'])) {
            foreach (var_escapa($valores['id_rango']) as $k => $v) {
                $this->brango_edad_comunidad->_do->id_grupoper = $idgrupoper;
                $this->brango_edad_comunidad->_do->id_caso = $idcaso;
                $this->brango_edad_comunidad->_do->id_rango
                    = (int)var_escapa($v, $db);
                $this->brango_edad_comunidad->_do->insert();
            }
        }

        if (isset($valores['id_sector'])) {
            foreach (var_escapa($valores['id_sector']) as $k => $v) {
                $this->bsector_social_comunidad->_do->id_grupoper = $idgrupoper;
                $this->bsector_social_comunidad->_do->id_caso = $idcaso;
                $this->bsector_social_comunidad->_do->id_sector
                    = (int)var_escapa($v, $db);
                $this->bsector_social_comunidad->_do->insert();
            }
        }

        if (isset($valores['id_vinculo_estado'])) {
            foreach (var_escapa($valores['id_vinculo_estado']) as $k => $v) {
                $this->bvinculo_estado_comunidad->_do->id_grupoper
                    = $idgrupoper;
                $this->bvinculo_estado_comunidad->_do->id_caso = $idcaso;
                $this->bvinculo_estado_comunidad->_do->id_vinculo_estado
                    = (int)var_escapa($v, $db);
                $this->bvinculo_estado_comunidad->_do->insert();
            }
        }

        if (isset($valores['id_filiacion'])) {
            foreach (var_escapa($valores['id_filiacion']) as $k => $v) {
                $this->bfiliacion_comunidad->_do->id_grupoper = $idgrupoper;
                $this->bfiliacion_comunidad->_do->id_caso = $idcaso;
                $this->bfiliacion_comunidad->_do->id_filiacion
                    = (int)var_escapa($v, $db);
                $this->bfiliacion_comunidad->_do->insert();
            }
        }

        if (isset($valores['id_profesion'])) {
            foreach (var_escapa($valores['id_profesion']) as $k => $v) {
                $this->bprofesion_comunidad->_do->id_grupoper = $idgrupoper;
                $this->bprofesion_comunidad->_do->id_caso= $idcaso;
                $this->bprofesion_comunidad->_do->id_profesion
                    = (int)var_escapa($v, $db);
                $this->bprofesion_comunidad->_do->insert();
            }
        }

        if (isset($valores['id_organizacion'])) {
            foreach (var_escapa($valores['id_organizacion']) as $k => $v) {
                $this->borganizacion_comunidad->_do->id_grupoper = $idgrupoper;
                $this->borganizacion_comunidad->_do->id_caso = $idcaso;
                $this->borganizacion_comunidad->_do->id_organizacion
                    = (int)var_escapa($v, $db);
                $this->borganizacion_comunidad->_do->insert();
            }
        }

        funcionario_caso($_SESSION['basicos_id']);
        return  $ret;
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
     * @see    PagBaseSimple
     */
    function datosBusqueda(&$w, &$t, &$db, $idcaso, &$subcons)
    {
        prepara_consulta_gen(
            $w, $t, $idcaso, 'victima_colectiva',
            '', '', false, array('antecedente_comunidad',
                    'rango_edad_comunidad', 'sector_social_comunidad',
                    'vinculo_estado_comunidad', 'filiacion_comunidad',
                    'profesion_comunidad', 'organizacion_comunidad'
            ),
            'id_grupoper', array('personas_aprox'), 'id_grupoper'
        );
    }


}

?>
