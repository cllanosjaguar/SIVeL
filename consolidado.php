<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
* Consolidado de víctimas.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: consolidado.php,v 1.57.2.6 2011/12/12 10:43:57 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Consolidado de víctimas.
 */

require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once 'HTML/QuickForm/Controller.php';
require_once 'HTML/QuickForm/Action/Display.php';
require_once 'HTML/QuickForm/Action/Next.php';
require_once 'HTML/QuickForm/Action/Back.php';
require_once 'HTML/QuickForm/Action/Jump.php';
require_once 'HTML/QuickForm/header.php';
require_once 'HTML/QuickForm/date.php';
require_once 'HTML/QuickForm/text.php';
require_once 'PagTipoViolencia.php';
require_once 'PagUbicacion.php';
require_once 'ResConsulta.php';
require_once 'misc.php';


/**
 * Responde a botón Consulta
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class AccionConsolidado extends HTML_QuickForm_Action
{

    /*
     *
     */

    /**
     * Presenta categorias que conformaran cada columna de la tabla.
     * Depuración
     *
     * @param handle  &$db    Conexión a BD
     * @param array   $cataux Cat
     * @param unknown $pResto pResto
     * @param unknown $ncol   Número de columnas
     *
     * @return void
     */
    function depTabla(&$db, $cataux, $pResto, $ncol)
    {
        $n = 1;
        $sep = "";
        foreach ($cataux as $l => $lc) {
            $p = objeto_tabla('parametros_reporte_consolidado');
            if (PEAR::isError($p)) {
                die($p->getMessage());
            }
            if ($n==($ncol+1)) {
                $rot = '(Resto)';
            } else if ($p->get($n)==1) {
                $rot = "(" . $p->rotulo.")";
            } else {
                $rot = '';
            }
            if ($n<($ncol+1) || ($pResto && $n==($ncol+1))) {
                $html_l = $sep . "<b>" . htmlentities($l)
                    . " " . htmlentities($rot) . ":</b>";
                echo $html_l;
                foreach ($lc as $cc) {
                    echo htmlentities($cc) . " ";
                }
                $sep = "; ";
            }
            $n++;
        }
    }


    /**
     * Llena arreglo de presuntos responsables que conformaran cada columna de
     *  la tabla.  Muestra en pantalla si muestra es true
     *
     * @param object  &$db     Conexión a BD
     * @param array   &$tpresp Tabla de presuntos responsables
     * @param unknown $muestra Muestra
     *
     * @return void
     */
    function muestraPresp(&$db, &$tpresp, $muestra)
    {
        $n = 1;
        $sep = "";
        $q = "SELECT DISTINCT id_p_responsable " .
            " FROM acto ORDER BY 1";
        //die("q es $q<br>");
        $result = hace_consulta($db, $q);
        $tpresp = array();
        $l = 1;
        while ($result->fetchInto($row)) {
            $d = objeto_tabla('presuntos_responsables');
            if (PEAR::isError($d)) {
                die($d->getMessage());
            }
            $d->get($row[0]);
            $tpresp[$row[0]] = $l;
            if ($muestra) {
                echo "<b>" . (int)$l . "</b>: "
                    . htmlentities($d->nombre) . ";";
            }
            $l++;
        }
    }



    /**
     * Realiza consulta
     *
     * @param unknown &$page      Página
     * @param unknown $actionName nombre de la acción
     *
     * @return void
     * @access public
     */
    function perform(&$page, $actionName)
    {
        $d = objeto_tabla('categoria');
        if (PEAR::isError($d)) {
            die($d->getMessage());
        }
        verifica_sin_CSRF($page->_submitValues);

        $fb =& DB_DataObject_FormBuilder::create($d);
        $db =& $d->getDatabaseConnection();

        $pFini      = var_post_escapa('fini', $db);
        $pFfin      = var_post_escapa('ffin', $db);
        $pFincdesde = var_post_escapa('fincdesde', $db);
        $pFinchasta = var_post_escapa('finchasta', $db);
        $pResto     = var_post_escapa('resto', $db);
        assert($pFinchasta['Y'] == '' || ($pFinchasta['Y'] >= 1900));
        assert($pFincdesde['Y'] == '' || ($pFincdesde['Y'] >= 1900));
        $pMuestra   = var_post_escapa('muestra', $db);
        $pIdClase   = (int)var_post_escapa('id_clase');
        $pIdMunicipio = (int)var_post_escapa('id_municipio', $db);
        $pIdDepartamento = (int)var_post_escapa('id_departamento', $db);

        $campos = array('caso_id' => 'Cód.');
        $tablas = "victima, caso, acto, municipio, departamento";
        $where = "";

        consulta_and_sinap($where, "victima.id_caso", "caso.id");
        consulta_and_sinap($where, "victima.id_persona", "acto.id_persona");
        consulta_and_sinap($where, "caso.id", "acto.id_caso");
        consulta_and(
            $db, $where, "caso.fecha",
            $GLOBALS['consulta_web_fecha_min'], ">="
        );
        consulta_and(
            $db, $where, "caso.fecha",
            $GLOBALS['consulta_web_fecha_max'], "<="
        );

        $tgeo = "";
        if ($pIdClase != '') {
            $tgeo = "ubicacion, ";
            consulta_and_sinap($where, "ubicacion.id_caso", "caso.id");
            consulta_and_sinap(
                $where, "ubicacion.id_departamento", $pIdDepartamento
            );
            consulta_and_sinap($where, "ubicacion.id_municipio", $pIdMunicipio);
            consulta_and_sinap($where, "ubicacion.id_clase", $pIdClase);
        } else if ($pIdMunicipio != '') {
            $tgeo = "ubicacion, ";
            consulta_and_sinap($where, "ubicacion.id_caso", "caso.id");
            consulta_and_sinap(
                $where, "ubicacion.id_departamento", $pIdDepartamento
            );
            consulta_and_sinap($where, "ubicacion.id_municipio", $pIdMunicipio);
        } else if ($pIdDepartamento != '') {
            $tgeo = "ubicacion, ";
            consulta_and_sinap($where, "ubicacion.id_caso", "caso.id");
            consulta_and_sinap(
                $where, "ubicacion.id_departamento", $pIdDepartamento
            );
        }
        $q = "SELECT MAX(col_rep_consolidado) FROM categoria;";
        $ncol = $db->getOne($q);

        if (PEAR::isError($ncol)) {
            die($ncol->getMessage());
        }
        if ($ncol <= 0) {
            die('Falta información para reporte consolidado en tabla categoria');
        }
        $d->orderBy('id');
        $d->find();
        while ($d->fetch()) {
            if (isset($d->col_rep_consolidado) && $d->col_rep_consolidado > 0) {
                $ic = chr($d->col_rep_consolidado+64);
            } else {
                $ic = chr($ncol+65);
            }
            $cataux[$ic] = isset($cataux[$ic]) ?
                $cataux[$ic]+array($d->id => $d->id) :
                array($d->id => $d->id);
        }
        ksort($cataux);
        if ($pMuestra == "tabla") {
            $this->depTabla($db, $cataux, $pResto, $ncol);
            echo "<p>Presuntos Responsables<br>";
        }
        $this->muestraPresp($db, $r_presp, $pMuestra == "tabla");
        $cat = $cataux;

        $nc2l = array();
        $sep = "";
        $tc = "";
        foreach ($cat as $c => $lc) {
            foreach ($lc as $nc) {
                $tc .= $sep . "'" . $nc . "'";
                $sep = ", ";
                $nc2l[$nc] = $c;
            }
        }

        if ($pFini['Y'] != '') {
            consulta_and(
                $db, $where, "caso.fecha",
                arr_a_fecha($pFini, true), ">="
            );
        }
        if ($pFfin['Y'] != '') {
            consulta_and(
                $db, $where, "caso.fecha",
                arr_a_fecha($pFfin, false), "<="
            );
        }

        $q = " SELECT caso.id, persona.id, " .
            " persona.nombres || ' ' || persona.apellidos, caso.fecha, " .
            "acto.id_categoria " .
            " FROM $tgeo persona, victima, caso, acto " .
            " WHERE persona.id=victima.id_persona " .
            " AND $where " ;
        $q = "(" . $q . ") ORDER BY 3";
        //echo "q es $q<br>";
        $result = hace_consulta($db, $q);

        $datv = array();
        $dn = array();
        $tv = 0;
        while ($result->fetchInto($row)) {
            $excl = false;
            $fhastallena = $pFinchasta['Y'] != '' && $pFinchasta['M'] != ''
                && $pFinchasta['d'] != '';
            $fdesdellena = $pFincdesde['Y'] != '' && $pFincdesde['M'] != ''
                && $pFincdesde['d'] != '';
            if ($fhastallena || $fdesdellena) {
                /* Se hace así porque puede haber casos que no
                    tengan asociado funcionario --cuando vienen de otro
                    banco (?), antiguos*/
                $q = "SELECT MIN(fecha_inicio) FROM funcionario_caso " .
                    " WHERE id_caso='" . $row[0] . "';";
                $rfc = hace_consulta($db, $q);
                if ($rfc->fetchInto($minf)) {
                    $arf = explode('-', $minf[0]);
                    $fhastamen = $pFinchasta['Y']  != ''
                        && ($pFinchasta['Y'] < $arf[0]
                        || ($pFinchasta['Y'] == $arf[0]
                        && $pFinchasta['M'] < $arf[1])
                            || ($pFinchasta['Y'] == $arf[0]
                            && $pFinchasta['M'] == $arf[1]
                            && $pFinchasta['d'] < $arf[2]
                        )
                    );
                    $fdesdemay = $pFincdesde['Y']  != ''
                        && ($pFincdesde['Y'] > $arf[0]
                        || ($pFincdesde['Y'] == $arf[0]
                        && $pFincdesde['M'] > $arf[1])
                            || ($pFincdesde['Y'] == $arf[0]
                            && $pFincdesde['M'] == $arf[1]
                            && $pFincdesde['d'] > $arf[2]
                        )
                    );

                    if ($fhastamen || $fdesdemen) {
                        if ($pMuestra == "tabla") {
                            //echo "Excluyendo :" . $row[0] . "<br>";
                            $excl = true;
                        }
                    }
                }
            }
            if (!$excl) {
                if (!isset($dv[$row[0]][$row[1]])) {
                    $datv[$tv] = array($row[0], $row[1], $row[2], $row[3]);
                    $tv++;
                }
                $dv[$row[0]][$row[1]][$nc2l[$row[4]]]=1;
            }
        }

        $depuraConsolidado = false;
        $suma = array();
        if ($pMuestra == "tabla") {
            echo "<table border='1'>\n";
            echo "<tr>";
            if ($depuraConsolidado) {
                echo "<td>IdCaso</td><td>IdVic</td>";
            }
            echo "<th>Fecha</th><th>Ubicación</th><th>Víctimas</th>";
        } elseif ($pMuestra == "csv") {
            header("Content-type: text/csv");
            echo '"Fecha", "Ubicación", "Víctimas", ""';
        } elseif ($pMuestra == 'latex') {
            //header("Content-type: application/x-latex");
            echo "<pre>";
            echo '\\textbf{Fecha} & \\textbf{Ubicacion} & \\textbf{Víctimas} ';
        }
        foreach ($cat as $idcat => $cp) {
            if ($pResto || $idcat != chr($ncol+65)) {
                if ($pMuestra == "tabla") {
                    echo "<th>". $idcat . "</th>";
                } elseif ($pMuestra == 'csv') {
                    echo ", \"" . $idcat . "\"";
                } elseif ($pMuestra == 'latex') {
                    echo "& \\textbf{" . $idcat . "} ";
                }
            }
            $suma[$idcat]=0;
        }
        if ($pMuestra == "tabla") {
            echo "<td>PR</td></tr>";
        } elseif ($pMuestra == 'csv') {
            echo ", PR\n";
        } elseif ($pMuestra == 'latex') {
            echo "& \textbf{PR} \\\\ \n\hline\n";
        }

        for ($v = 0;$v < $tv; $v++) {
            $idcaso = $datv[$v][0];
            $idvic = $datv[$v][1];
            $nom = $datv[$v][2];
            $fecha = $datv[$v][3];
            $ubi = "";
            $u =&  objeto_tabla('ubicacion');
            $u->id_caso = $idcaso;
            if ($u->find() == 0) {
                die("<br/><font color='red'>Caso sin ubicacion: " .
                    "$idcaso -- estadística incompleta!!!</font>"
                );
            }
            $u->fetch();
            $d = $u->getLink('id_departamento');
            $ubi = trim($d->nombre);
            if (isset($u->id_municipio)) {
                $m =&  objeto_tabla('municipio');
                $m->id = $u->id_municipio;
                $m->id_departamento = $u->id_departamento;
                if ($m->find()==0) {
                    die("Caso " . $idcaso .
                        " referencia municipio inexistente " .
                        $m->id.", " . $m->id_departamento
                    );
                }
                $m->fetch();
                $ubi .= " - ".trim($m->nombre);
            }

            if ($pMuestra == "tabla") {
                echo "<tr>";
                if ($depuraConsolidado) {
                    echo "<td>". (int)$idcaso . "</td><td>"
                        . (int)$idvic . "</td>";
                }
                echo "<td>" . htmlentities($fecha) . "</td><td>" .
                    htmlentities($ubi) . "</td><td>" .
                    trim(htmlentities($nom)) . "</td>";
            } elseif ($pMuestra == 'csv') {
                $adjunto_l = $fecha . ", " . $ubi . ", ".trim($nom).", ";
                echo $adjunto_l;
            } elseif ($pMuestra == 'latex') {
                $lt = txt2latex($fecha) . " & " . txt2latex($ubi).
                    " & " . txt2latex(trim($nom)) . " ";
                echo htmlentities($lt);
            }
            foreach ($cat as $idcat => $cp) {
                if (isset($dv[$idcaso][$idvic][$idcat])) {
                    if ($pResto || $idcat != chr($ncol+65)) {
                        if ($pMuestra == "tabla") {
                            echo "<td>X</td>";
                        } elseif ($pMuestra == 'csv') {
                            echo ", X";
                        } elseif ($pMuestra == 'latex') {
                            echo "& X ";
                        }
                    }
                    if ($dv[$idcaso][$idvic][$idcat] == 1) {
                        $suma[$idcat]++;
                    } else {
                        die("Sobreconteo por caso " . $idcaso .
                            " que saldría más de una vez en la columna " .
                            $idcat
                        );
                    }
                    $dv[$idcaso][$idvic][$idcat]++;
                } else {
                    if ($pResto || $idcat != chr($ncol+65)) {
                        if ($pMuestra == "tabla") {
                            echo "<td>&nbsp;</td>";
                        } elseif ($pMuestra == 'csv') {
                            echo ", ";
                        } elseif ($pMuestra == 'latex') {
                            echo "& ";
                        }
                    }
                }
            }
            $acto =&  objeto_tabla('acto');
            $acto->id_persona = $idvic;
            $acto->id_caso = $idcaso;
            if ($acto->find()==0) {
                $acto = null;
                echo_esc(
                    "Víctima sin presunto responsable '$idvic', "
                    . "'$nom' en caso '$idcaso' -- estadística incompleta!!!"
                );
            } else {
                $apr = array();
                $pr_sep = $presp = "";

                while ($acto->fetch()) {
                    $d = $acto->getLink('id_p_responsable');
                    $apr[$d->id] = $r_presp[$d->id];
                }
                foreach ($apr as $id => $pr) {
                    $presp .= $pr_sep . $pr;
                    $pr_sep = ";";
                }
            }

            if ($pMuestra == "tabla") {
                echo "<td>" . htmlentities($presp) . "</td></tr>\n";
            } elseif ($pMuestra == 'csv') {
                $adjunto_l = ",$presp \n";
                echo $adjunto_l;
            } elseif ($pMuestra == 'latex') {
                echo htmlentities("& $presp \\\\\n \hline\n");
            }

        }
        if ($pMuestra == "tabla") {
            echo "<tr><td></td><td></td><td></td>";
        } elseif ($pMuestra == 'csv') {
            echo '"", "", "", ""';
        } elseif ($pMuestra == 'latex') {
            echo ' & & ';
        }

        foreach ($cat as $idcat => $cp) {
            if ($pResto || $idcat != chr($ncol+65)) {
                if ($pMuestra == "tabla") {
                    echo "<td>" . (int)$suma[$idcat] . "</td>";
                } elseif ($pMuestra == 'csv') {
                    echo ", " . (int)$suma[$idcat];
                } elseif ($pMuestra == 'latex') {
                    echo "& " . (int)$suma[$idcat] . " ";
                }
            }
        }
        if ($pMuestra == "tabla") {
            echo "</tr>";
            echo "</table>";
        } elseif ($pMuestra == 'csv') {
            echo "\n";
        } elseif ($pMuestra == 'latex') {
            echo "\\\\\n \hline\n";
        }
    }
}


/**
 * Formulario de reporte consolidado
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class PagConsolidado extends HTML_QuickForm_Page
{

    /**
     * Constructora.
     *
     * @return void
     */
    function PagConsolidado()
    {
        $this->HTML_QuickForm_Page('consolidado', 'post', '_self', null);

        $this->addAction('id_departamento', new CamDepartamento());
        $this->addAction('id_municipio', new CamMunicipio());


        $this->addAction('consulta', new AccionConsolidado());
    }


    /**
     * Construye formulario
     *
     * @return void
     * @access public
     */
    function buildForm()
    {
        encabezado_envia();
        $this->_formBuilt = true;
        $x =&  objeto_tabla('departamento');
        $db = $x->getDatabaseConnection();

        $e =& $this->addElement('header', null, 'Reporte consolidado');

        list($dep, $mun, $cla) = PagUbicacion::creaCamposUbicacion(
            $db, $this, 'victimaIndividual',
            $this->bpersona->_do->id_departamento,
            $this->bpersona->_do->id_municipio
        );

        $this->addElement($dep);
        $this->addElement($mun);
        $this->addElement($cla);

        $cy = date('Y');
        if ($cy < 2005) {
            $cy = 2005;
        }
        $e =& $this->addElement(
            'date', 'fini', 'Desde: ',
            array('language' => 'es', 'addEmptyOption' => true,
            'minYear' => $GLOBALS['anio_min'], 'maxYear' => $cy
            )
        );
        $e =& $this->addElement(
            'date', 'ffin', 'Hasta',
            array('language' => 'es', 'addEmptyOption' => true,
            'minYear' => $GLOBALS['anio_min'], 'maxYear' => $cy
            )
        );
        if (isset($_SESSION['id_funcionario'])) {
            $aut_usuario = "";
            include $_SESSION['dirsitio'] . "/conf.php";
            autenticaUsuario($dsn, $accno, $aut_usuario, 0);
            if (in_array(42, $_SESSION['opciones'])) {
                $e =& $this->addElement(
                    'date', 'fincdesde',
                    'Ingresado en base desde', array('language' => 'es',
                    'addEmptyOption' => true,
                    'minYear' => $GLOBALS['anio_min'], 'maxYear' => $cy
                    )
                );
                $e =& $this->addElement(
                    'date', 'finchasta',
                    'Ingresado en base hasta', array('language' => 'es',
                    'addEmptyOption' => true,
                    'minYear' => $GLOBALS['anio_min'], 'maxYear' => $cy
                    )
                );
                $e =& $this->addElement(
                    'checkbox', 'resto',
                    'Agregar columna con resto', ''
                );
            }
        }

        $ae = array();
        $t =&  $this->createElement(
            'radio', 'muestra', 'tabla',
            'Tabla HTML', 'tabla'
        );
        $ae[] =& $t;
        $ae[] =&  $this->createElement(
            'radio', 'muestra', 'csv',
            'Formato CSV (hoja de cálculo)', 'csv'
        );
        $ae[] =&  $this->createElement(
            'radio', 'muestra', 'latex',
            'LaTeX', 'latex'
        );
        $this->addGroup($ae, null, 'Forma de presentación', '&nbsp;', false);
        $t->setChecked(true);

        $prevnext = array();
        $sel =& $this->createElement(
            'submit',
            $this->getButtonName('consulta'), 'Consulta'
        );
        $prevnext[] =& $sel;

        $this->addGroup($prevnext, null, '', '&nbsp;', false);

        $tpie = "<div align=right><a href=\"index.php\">" .
            "Menú Principal</a></div>";
        $e =& $this->addElement('header', null, $tpie);

        if (!isset($_POST['evita_csrf'])) {
            $_SESSION['sin_csrf'] = mt_rand(0, 1000);
        }
        $this->addElement('hidden', 'evita_csrf', $_SESSION['sin_csrf']);

        $this->setDefaultAction('consulta');

    }

}

$aut_usuario = "";
autenticaUsuario($dsn, $accno, $aut_usuario, 43);

$wizard =& new HTML_QuickForm_Controller('Consolidado', false);
$consweb = new PagConsolidado();

$wizard->addPage($consweb);


$wizard->addAction('display', new HTML_QuickForm_Action_Display());
$wizard->addAction('jump', new HTML_QuickForm_Action_Jump());

$wizard->addAction('process', new AccionConsolidado());

$wizard->run();
?>
