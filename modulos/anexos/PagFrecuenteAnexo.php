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
 * @version   CVS: $Id: PagFrecuenteAnexo.php,v 1.13.2.3 2011/10/13 09:57:50 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Pesta�a Fuentes Frecuentes con Anexo del multi-formulario capturar caso
 */

require_once 'PagFuentesFrecuentes.php';
require_once 'ResConsulta.php';


/**
 * P�gina fuentes frecuentes con anexo.
 * Ver documentaci�n de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see PagFuentesFrecuentes
 */
class PagFrecuenteAnexo extends PagFuentesFrecuentes
{

    var $titulo = 'Fuentes Frecuentes';


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
        if ($this->bescrito_caso->_do->id_prensa != null
            && $this->bescrito_caso->_do->fecha != null
        ) {
            $idcaso = $this->bescrito_caso->_do->id_caso;
            $vf = "'{$this->bescrito_caso->_do->fecha}'";
            $vp = "'{$this->bescrito_caso->_do->id_prensa}'";
            $q =  "UPDATE anexo SET fecha_prensa=NULL, id_prensa=NULL" .
                " WHERE id_caso='$idcaso' AND fecha_prensa=$vf " .
                " AND id_prensa=$vp";
            $db = $this->bescrito_caso->_do->getDatabaseConnection();
            hace_consulta($db, $q, false) ;
        }

        parent::elimina($valores);
    }


    /**
     * Constructora.
     * Ver documentaci�n completa en clase base.
     *
     * @param string $nomForma Nombre
     *
     * @return void
     */
    function PagFrecuenteAnexo($nomForma)
    {
        parent::PagFuentesFrecuentes($nomForma);
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

        parent::formularioAgrega($db, $idcaso);

        if (!isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda'
        ) {
            if ($this->bescrito_caso->_do->id_prensa != null) {
                $cor = "OR (id_prensa=" .
                    "'{$this->bescrito_caso->_do->id_prensa}' " .
                    "AND fecha_prensa='{$this->bescrito_caso->_do->fecha}')";
            } else {
                $cor = "";
            }
            $condb = "AND id_fuente_directa IS NULL " .
            "AND (id_prensa IS NULL $cor)  " ;
            $an = $this->addElement(
                'select', 'id_anexo', 'Anexo',
                array()
            );
            $q = "SELECT  id, archivo FROM anexo " .
                "WHERE id_caso='" . (int)$_SESSION['basicos_id'] . "' " .
                $condb .
                "ORDER BY archivo ";
            //echo $q;
            $options = array('' => '') +
                htmlentities_array($db->getAssoc($q));
            $an->loadArray($options);
        }

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
        parent::formularioValores($db, $idcaso);

        $puesto = false;
        $sel = $this->getElement('id_anexo');
        if ($this->bescrito_caso->_do->id_prensa != null) {
            $danexo = objeto_tabla('anexo');
            $danexo->id_caso = $_SESSION['basicos_id'];
            $danexo->id_prensa = $this->bescrito_caso->_do->id_prensa;
            $danexo->id_fecha_prensa = $this->bescrito_caso->_do->fecha;
            $danexo->find();
            if ($danexo->fetch()) {
                $sel->setValue($danexo->id);
                $puesto = true;
            }
        }
        if ((!isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda'
        ) && !$puesto
        ) {
            $sel->setValue('');
        }
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
        $idcaso = $_SESSION['basicos_id'];

        $db = $this->iniVar();

        $r = parent::procesa($valores);
        if (!es_objeto_nulo($this->bescrito_caso->_do->id_prensa)
            && !es_objeto_nulo($this->bescrito_caso->_do->fecha)
        ) {
            $vf = "'{$this->bescrito_caso->_do->fecha}'";
            $vp = "'{$this->bescrito_caso->_do->id_prensa}'";
            if (isset($valores['id_anexo'])
                && $valores['id_anexo'] != ''
            ) {
                $ida = var_escapa($valores['id_anexo'], $db);
                $q =  "UPDATE anexo SET fecha_prensa=$vf, id_prensa=$vp " .
                    "WHERE id_caso='$idcaso' AND id='$ida'";
            } else {
                $q =  "UPDATE anexo SET fecha_prensa=NULL, id_prensa=NULL" .
                    " WHERE id_caso='$idcaso' AND fecha_prensa=$vf " .
                    " AND id_prensa=$vp";
            }
            //echo $q;
            hace_consulta($db, $q, false) ;
        }

        funcionario_caso($_SESSION['basicos_id']);
        return $r;
    }



}

?>
