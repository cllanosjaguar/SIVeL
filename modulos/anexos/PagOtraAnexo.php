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
 * @version   CVS: $Id: PagOtraAnexo.php,v 1.11.2.4 2011/11/22 15:13:24 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Pesta�a Otras Fuentes con Anexo del multi-formulario capturar caso
 */

require_once 'PagOtrasFuentes.php';
require_once 'ResConsulta.php';


/**
 * P�gina otras fuentes con anexo.
 * Ver documentaci�n de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see PagOtrasFuentes
 */
class PagOtraAnexo extends PagOtrasFuentes
{

    var $titulo = 'Otras Fuentes';

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
        if ($this->bfuente_directa_caso->_do->id_fuente_directa != null) {
            $idcaso = $this->bfuente_directa_caso->_do->id_caso;
            $vf = "'{$this->bfuente_directa_caso->_do->id_fuente_directa}'";
            $q =  "UPDATE anexo SET id_fuente_directa=NULL " .
                "WHERE id_caso='$idcaso' AND id_fuente_directa=$vf";
            $db = $this->bfuente_directa_caso->_do->getDatabaseConnection();
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
    function PagOtraAnexo($nomForma)
    {
        parent::PagOtrasFuentes($nomForma);
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
            if ($this->bfuente_directa_caso->_do->id_fuente_directa != null) {
                $cor = "OR id_fuente_directa=" .
                    "'{$this->bfuente_directa_caso->_do->id_fuente_directa}' ";
            } else {
                $cor = "";
            }
            $condb = "WHERE id_caso='" . (int)$_SESSION['basicos_id'] . "' " .
                "AND (id_prensa IS NULL) " .
                "AND (id_fuente_directa IS NULL $cor)  " ;
            $an = $this->addElement(
                'select', 'id_anexo', 'Anexo',
                array()
            );
            $q = "SELECT  id, archivo FROM anexo " .
                $condb .
                "ORDER BY archivo ";
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
        if ($this->bfuente_directa_caso->_do->id_fuente_directa != null) {
            $danexo = objeto_tabla('anexo');
            $danexo->id_caso = $_SESSION['basicos_id'];
            $danexo->id_fuente_directa =
                $this->bfuente_directa_caso->_do->id_fuente_directa;
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
        if ($this->bfuente_directa_caso->_do->id_fuente_directa != null) {
            $vf = "'{$this->bfuente_directa_caso->_do->id_fuente_directa}'";
            if (isset($valores['id_anexo']) && $valores['id_anexo'] != '') {
                $ida = var_escapa($valores['id_anexo'], $db);
                $q =  "UPDATE anexo SET id_fuente_directa=$vf " .
                    "WHERE id_caso='$idcaso' AND id='$ida'";
            } else {
                $q =  "UPDATE anexo SET id_fuente_directa=NULL " .
                    " WHERE id_caso='$idcaso' AND id_fuente_directa=$vf";
            }
            //echo $q;
            hace_consulta($db, $q, false) ;
        }

        funcionario_caso($_SESSION['basicos_id']);
        return $r;
    }

}

?>
