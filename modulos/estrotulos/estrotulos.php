<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Estadísticas victimas individuales por rótulos
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2009 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: estrotulos.php,v 1.16.2.6 2011/11/15 12:03:19 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Estadísticas de victimas individuales por rótulos
 */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once "misc.php";
require_once "DataObjects/Filiacion.php";

require_once 'HTML/QuickForm/Controller.php';

require_once 'HTML/QuickForm/Action/Display.php';
require_once 'HTML/QuickForm/Action/Next.php';
require_once 'HTML/QuickForm/Action/Back.php';
require_once 'HTML/QuickForm/Action/Jump.php';
require_once 'HTML/QuickForm/header.php';
require_once 'HTML/QuickForm/date.php';
require_once 'HTML/QuickForm/text.php';

require_once "PagTipoViolencia.php";
require_once "ResConsulta.php";

/**
 * Responde a botón consulta
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class AccionEstadisticasIndRot extends HTML_QuickForm_Action
{

    /**
     * Ejecuta acción
     *
     * @param object &$page      Página
     * @param string $actionName Acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        $d = objeto_tabla('departamento');
        if (PEAR::isError($d)) {
            die($d->getMessage());
        }
        $fb =& DB_DataObject_FormBuilder::create($d);
        $db =& $d->getDatabaseConnection();


        $pFini = var_post_escapa('fini');
        $pFfin = var_post_escapa('ffin');
        $pTipo = var_post_escapa('id_tipo_violencia');
        $pSupra = var_post_escapa('id_supracategoria');
        $pSegun = var_post_escapa('segun');
        $pMuestra = var_post_escapa('muestra');
        $pMunicipio = var_post_escapa('municipio');
        $pDepartamento = var_post_escapa('departamento');
        $pSinFiliacion = var_post_escapa('sinfiliacion');

        $cons = 'cons';
        $cons2="cons2";
        $where = "";

        consulta_and(
            $db, $where, "caso.fecha", $GLOBALS['consulta_web_fecha_min'],
            ">="
        );
        consulta_and(
            $db, $where, "caso.fecha", $GLOBALS['consulta_web_fecha_max'],
            "<="
        );

        if ($pFini['Y'] != '') {
            consulta_and($db, $where, "caso.fecha", arr_a_fecha($pFini, true), ">=");
        }
        if ($pFfin['Y'] != '') {
            consulta_and($db, $where, "caso.fecha", arr_a_fecha($pFfin, false), "<=");
        }

        $tablaSegun = $titSegun = "";
        $tCat = "";
        $tQue = "";
        $condSegun = "";
        $distinct = "DISTINCT "; // Para contar victimizaciones
        if ($pSinFiliacion != 1) {
            consulta_and(
                $db, $where, "victima.id_filiacion",
                DataObjects_Filiacion::idSinInfo(), "<>"
            );
        }


        if ($pSegun == 'id_rango_edad') {
            consulta_and_sinap($where, "victima.id_rango_edad","rango_edad.id");
//$tablas .= ", rango_edad"; */
            $campoSegun = "id_rango_edad";
            $cfSegun = "rango_edad.rango";
            $tablaSegun = "rango_edad, ";
            $condSegun = "AND rango_edad.id=$cons2.id_rango_edad";
            $titSegun = 'Edad';
        }  elseif ($pSegun == 'sexo') {
            $campoSegun = "sexo";
            $cfSegun = "sexo";
            $tablaSegun = "";
            $condSegun = "";
            $titSegun = 'Sexo';
        } elseif ($pSegun == 'id_p_responsable') {
            $distinct = ""; // Para contar actos
            $cfSegun = 'presuntos_responsables.nombre';
            $tablaSegun = "presuntos_responsables, ";
            $condSegun = "AND presuntos_responsables.id=$cons2.id_p_responsable";
            $titSegun = 'P. Responsable';
            consulta_and_sinap(
                $where, "acto.id_p_responsable",
                "presuntos_responsables.id"
            );
            consulta_and_sinap($where, "victima.id_persona","acto.id_persona");
            $tQue .= "";
            $campoSegun = "acto.id_p_responsable";
        } elseif ($pSegun == 'id_sector_social') {
            $campoSegun = "id_sector_social";
            $cfSegun = "sector_social.nombre";
            $tablaSegun = "sector_social, ";
            consulta_and_sinap(
                $where, "victima.id_sector_social",
                "sector_social.id"
            );
            $condSegun = "AND sector_social.id=$cons2.id_sector_social";
            $titSegun = 'Sector Social';
        } elseif ($pSegun == 'meses') {
            $campoSegun = "extract(year from fecha) || '-' " .
                "|| lpad(cast(extract(month from fecha) as text), 2, " .
                "cast('0' as text))";
            $cfSegun = "meses";
            $tablaSegun= "";
            $titSegun = 'Mes';
        } elseif ($pSegun == 'id_filiacion'
            || $pSegun == 'id_organizacion'
            || $pSegun == 'id_sector_social'
        ) { # or $pSegun = '!= '') {
            $campoSegun = $pSegun;
            $ant = explode("_", $pSegun);
            $tablaSegun = "";
            $tsep = "";
            for ($i = 1; $i < count($ant); $i++) {
                $tablaSegun .= $tsep . $ant[$i];
                $tsep = "_";
            }
            $cfSegun= $tablaSegun . ".nombre";
            consulta_and_sinap(
                $where, "victima.$campoSegun",
            "$tablaSegun.id"
            );
            $condSegun = "AND $tablaSegun.id=$cons2.$campoSegun";
            $titSegun = $GLOBALS['etiqueta'][$tablaSegun];
            $tablaSegun .= ",";
        }

        $colenc = 0; // Número de columnas con etiquetas en lugar de datos
        $cab = array();
        if ($titSegun != "") {
            $cab[] = $titSegun;
            $colenc++;
        }
        $tDep = $gDep = "";
        if ($pDepartamento == "1") {
            $tDep = "trim(departamento.nombre), ";
            $gDep = "departamento.nombre, ";
            $cab[] = 'Departamento';
            $colenc++;
        }
        $tMun = $gMun = "";
        if ($pMunicipio == "1") {
            $tMun = "trim(municipio.nombre), ";
            $gMun = "municipio.nombre, ";
            $cab[] = 'Municipio';
            $colenc++;
        }
        $cab[] = 'Rotulo';
        $cab[] = 'N. Víctimizaciones';
        $tCat .= 'acto';
        $tQue .= "victima, persona, $tCat, ";
        consulta_and_sinap($where, "victima.id_caso","caso.id");
        consulta_and_sinap($where, "victima.id_persona","persona.id");
        consulta_and_sinap($where, "persona.id","acto.id_persona");
        consulta_and_sinap($where, "acto.id_caso","caso.id");
        consulta_and_sinap($where, "acto.id_categoria","categoria.id");
        $pQ1='id_persona, ';
        $pQ1sel = 'persona.id, ';
        $tablas = $tablaSegun . "$tQue caso, categoria";
        $campos = array('caso_id' => 'Cód.');


        if ($pTipo != '') {
            consulta_and($db, $where, "categoria.id_tipo_violencia", $pTipo);
        }
        if ($pSupra != '') {
            consulta_and($db, $where, "categoria.id_supracategoria", $pSupra);
        }

        $campoSegun2=$cfSegun2=$cfSegun3=$pSegun2="";
        if ($pSegun != "") {
            $pSegun2=", ".$pSegun;
            $cfSegun2=$cfSegun . ", ";
            $cfSegun3="trim(" . $cfSegun . "), ";
            $campoSegun2=", ".$campoSegun;
        }
        $q1="CREATE VIEW $cons ($pQ1 id_caso, id_tipo_violencia, id_supracategoria, id_categoria, col_rep_consolidado".$pSegun2 .") AS SELECT $pQ1sel caso.id, categoria.id_tipo_violencia, categoria.id_supracategoria, $tCat.id_categoria, categoria.col_rep_consolidado $campoSegun2 FROM ".$tablas
            ."  WHERE caso.id<>'".$GLOBALS['idbus'] . "'"
            ;
        // Evitamos sobreconteos de duplicados en DIH y DH excluyendo los de DH
        $q1 .=  " AND id_categoria NOT IN (select id from categoria where contada_en IS NOT NULL) ";
/*        <>'10' AND id_categoria<>'15' AND " .
            "id_categoria<>'18' AND id_categoria<>'12' AND " .
            "id_categoria<>'13' AND " .
            "id_categoria<>'19' AND id_categoria<>'102' AND " .
            "id_categoria<>'150' "; */
        if ($where != "") {
            $q1 .= " AND ".$where;
        }
        hace_consulta($db, "DROP VIEW $cons2", false, false);
        hace_consulta($db, "DROP VIEW $cons", false, false);
        //echo "q1 es $q1<hr>"; die("x");
        $result = hace_consulta($db, $q1);
        if (PEAR::isError($result)) {
            die("Problema ejecutando consulta preliminar: '$q1', " .
                $result->getMessage()."'"
            );
        }

        $q2="CREATE VIEW $cons2 (id_persona, id_caso, id_tipo_violencia, id_supracategoria, id_categoria, col_rep_consolidado" . $pSegun2 . ", id_departamento, id_municipio) ";
        $q2 .= "AS SELECT $distinct $cons.id_persona, $cons.id_caso, id_tipo_violencia, id_supracategoria, id_categoria, col_rep_consolidado" . $pSegun2 . ", ubicacion.id_departamento, ubicacion.id_municipio FROM ubicacion, $cons
            WHERE $cons.id_caso = ubicacion.id_caso";
/*        foreach (array('municipio_caso', 'clase_caso') as $t) {
            $q2 .= " UNION  SELECT $cons.$cCons, id_tipo_violencia, id_supracategoria, id_categoria, col_rep_consolidado" . $pSegun2 . ", $t.id_departamento, $t.id_municipio FROM
$t, $cons WHERE $cons.id_caso = $t.id_caso";
}  */

        //echo "q2 es $q2<hr>"; die("x");
        $result = hace_consulta($db, $q2);
        if (PEAR::isError($result)) {
            die("Problema ejecutando consulta: '$q2', " .
                $result->getMessage()."'"
            );
        }

        // El método para departamento y municipio es machete porque cuando
        // no hay asigna santa-marta y así saldría e.g PUTUMAYO    SANTA MARTA
        $q3 = "SELECT $cfSegun3 $tDep $tMun
            TRIM(parametros_reporte_consolidado.rotulo),
            COUNT(cast($cons2.id_persona as text) || ' ' || cast($cons2.id_caso as text))
            FROM $tablaSegun departamento, municipio,
            parametros_reporte_consolidado,
            $cons2
            WHERE
            (($cons2.id_departamento IS NULL AND departamento.id = 47) OR
            departamento.id = $cons2.id_departamento)
            AND (($cons2.id_municipio IS NULL AND municipio.id = 1
            AND municipio.id_departamento = 47)  OR
            (municipio.id = $cons2.id_municipio
            AND municipio.id_departamento = $cons2.id_departamento))
            AND col_rep_consolidado = parametros_reporte_consolidado.no_columna
            $condSegun
            GROUP BY $cfSegun2 $gDep $gMun parametros_reporte_consolidado.rotulo
            ORDER BY $cfSegun2 $gDep $gMun parametros_reporte_consolidado.rotulo
            ";

        //echo "q3 es $q3<hr>"; die("x");
        $result = hace_consulta($db, $q3);
        if (PEAR::isError($result)) {
            die("Problema ejecutando consulta: '$q3', " .
                $result->getMessage()."'"
            );
        }


        $tcol = array();

        $tfil = array();
        $celda = array();
        for ($i = 0; $i < count($cab)-2; $i++) {
            $tcol[$cab[$i]]=1;
        }
        $q4='SELECT no_columna, rotulo
            FROM parametros_reporte_consolidado
            ORDER BY 1';
        $rcon = hace_consulta($db, $q4);
        if (PEAR::isError($rcon)) {
            die("Problema ejecutando consulta: '$q4', " .
                $rcon->getMessage()."'"
            );
        }
        $row = array();
        while ($rcon->fetchInto($row)) {
            $tcol[$row[1]]=1;
        }

        if ($pMuestra == 'tabla') {

            while ($result->fetchInto($row)) {
                $nc = count($row);
                $nf = "";
                for ($i = 0; $i < $nc-2; $i++) {
                    $nf .= $row[$i];
                }
                for ($i = 0; $i < $nc-2; $i++) {
                    $celda[$nf][$cab[$i]] = $row[$i];
                }
                //    echo $nf . "<br>";
                $tfil[$nf]=1;
                //$tcol[$row[$nc-2]]=1;
                $celda[$nf][$row[$nc-2]] = $row[$nc-1];
            }
            ksort($tfil);

            encabezado_envia();
            echo "<table border=\"1\">";
            echo "<tr>";
            $scol = array();
            foreach ($tcol as $k => $t) {
                echo "<th>" . htmlentities($k) . "</th>";
                $scol[$k] = 0;
            }
            echo "<th>Total</th>";
            echo "</tr>\n";

            $row = array();
            foreach ($tfil as $f => $t1) {
                echo "<tr>"; //<td>" . $f . "</td>";
                $sfil = 0;
                $ncol = 0;
                foreach ($tcol as $c => $t2) {
                    echo "<td>";
                    if (isset($celda[$f][$c])) {
                        echo htmlentities($celda[$f][$c]);
                        if ($ncol >= $colenc) {
                            $scol[$c] += (int)$celda[$f][$c];
                            $sfil += (int)$celda[$f][$c];
                        }
                    } else {
                        echo "0";
                    }
                    echo "</td>";
                    $ncol++;
                }
                echo "<td><b>$sfil</b></td>";
                echo "</tr>\n";
            }

            if (count($tfil) > 1) {
                echo "<tr>";
                $sfil = 0;
                $ncol = 0;
                foreach ($tcol as $k => $t) {
                    echo "<td>";
                    if ($ncol < $colenc) {
                        if ($ncol == 0) {
                            echo "<b>Total</b>";
                        }
                    } else {
                        echo "<b>" . $scol[$k] . "</b>";
                        $sfil += (int)$scol[$k];
                    }
                    echo "</td>";
                    $ncol++;
                }
                echo "<td><b>$sfil</b></td>";
                echo "</tr>\n";
            }
            echo "</table>";
            pie_envia();
        } else { // CSV
            // http://www.creativyst.com/Doc/Articles/CSV/CSV01.htm
            header("Content-type: text/csv");
            $sep = '';
            foreach ($cab as $k => $t) {
                $adjunto_t = $sep . '"' . str_replace('"', '""', $t) . '"';
                echo $adjunto_t;
                $sep = ', ';
            }
            echo "\n";
            $row = array();
            while ($result->fetchInto($row)) {
                $sep = '';
                foreach ($cab as $k => $t) {
                    $adjunto_t = $sep . $row[$k];
                    echo $adjunto_r;
                    $sep = ', ';
                }
                echo "\n";
            }
        }

/*        $r=new ExportaResultados($db, $result); $r->aHtml(); */

    }
}


/**
 * Formulario de Estadísticas Individuales por rótulos
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class PagEstadisticasIndRot extends HTML_QuickForm_Page
{

    /**
     * Constructora.
     * Ver documentación completa en clase base.
     *
     * @param string $nomForma Nombre
     * @param string $mreq     Mensaje de dato requerido
     *
     * @return void
     */
function PagEstadisticasIndRot()
{
    $this->HTML_QuickForm_Page(
        'estadisticas', 'post', '_self',
        null
    );

    $this->addAction('id_tipo_violencia', new CamTipoViolencia());

    $this->addAction('consulta', new AccionEstadisticasIndRot());
}


function idTipoViolencia()
{
    $ntipoviolencia= null;
    if (isset($this->_submitValues['id_tipo_violencia'])) {
        $ntipoviolencia = (int)$this->_submitValues['id_tipo_violencia'] ;
    } else if (isset($_SESSION['id_tipo_violencia'])) {
        $ntipoviolencia = $_SESSION['id_tipo_violencia'] ;
    }
    return $ntipoviolencia;
}


function idSupracategoria()
{
    $nclase = null;
    if (isset($this->_submitValues['id_supracategoria'])) {
        return  (int)$this->_submitValues['id_supracategoria'] ;
    }
    return null;
}


function buildForm()
{
    encabezado_envia();
    $this->_formBuilt = true;
    $x =&  objeto_tabla('departamento');
    $db = $x->getDatabaseConnection();

    $e =& $this->addElement('header', null, 'Estadísticas Victimas Individuales');
    $e =& $this->addElement('hidden', 'num', (int)$_REQUEST['num']);

//    $e =& $this->addElement('static', 'fini', 'Victimas ');

    $cy = date('Y');
    if ($cy < 2005) {
        $cy = 2005;
    }
    $e =& $this->addElement(
        'date', 'fini', 'Desde: ',
        array('language' => 'es', 'addEmptyOption' => true,
        'minYear' => '1990', 'maxYear' => $cy
        )
    );
    $e->setValue(($cy - 1) . "-01-01");

    $e =& $this->addElement(
        'date', 'ffin', 'Hasta',
        array('language' => 'es', 'addEmptyOption' => true,
        'minYear' => '1990', 'maxYear' => $cy
        )
    );
    $e->setValue(($cy  - 1) . "-12-31");

    $tipo =& $this->addElement(
        'select', 'id_tipo_violencia',
        'Tipo de violencia: ', array()
    );
    $options= array('' => '') + htmlentities_array(
        $db->getAssoc(
            "SELECT  id, nombre FROM tipo_violencia " .
            "ORDER BY id"
        )
    );
    $tipo->loadArray($options);
    $tipo->updateAttributes(
        array('onchange' =>
        'envia(\'estadisticas:id_tipo_violencia\')'
        )
    );

    $supra =& $this->addElement(
        'select', 'id_supracategoria',
        'Supracategoria: ', array()
    );

    $ntipoviolencia = $this->idTipoViolencia();
    if ($ntipoviolencia != null) {
        $tipo->setValue($ntipoviolencia);
        $options= array('' => '') + htmlentities_array(
            $db->getAssoc(
                "SELECT  id, nombre FROM supracategoria " .
                "WHERE id_tipo_violencia='$ntipoviolencia' ORDER BY id"
            )
        );
        $supra->loadArray($options);
    }
    $nsupra = $this->idSupracategoria();

    $sel =& $this->addElement(
        'select',
        'segun', 'Según'
    );
    $sel->loadArray(
        array('' => '',
        'id_p_responsable' => 'ACTOS '
            . strtoupper($GLOBALS['etiqueta']['p_responsable']),
        'id_rango_edad' => strtoupper($GLOBALS['etiqueta']['rango_edad']),
        'sexo' => strtoupper($GLOBALS['etiqueta']['sexo']),
        'id_filiacion' => strtoupper($GLOBALS['etiqueta']['filiacion']),
        'id_profesion' => strtoupper($GLOBALS['etiqueta']['profesion']),
        'id_sector_social' => strtoupper($GLOBALS['etiqueta']['sector_social']),
        'id_organizacion' => strtoupper($GLOBALS['etiqueta']['organizacion']),
        'meses' => 'MESES',
        'id_profesion' => 'PROFESION',
    )
    );


    $ae = array();
    $sel =& $this->createElement(
        'checkbox',
        'departamento', 'Departamento', 'Departamento'
    );
    //$sel->setValue(true);
    $ae[] =& $sel;

    $sel =& $this->createElement(
        'checkbox',
        'municipio', 'Municipio', 'Municipio'
    );
    //$sel->setValue(true);
    $ae[] =& $sel;
    $this->addGroup($ae, null, 'Localización', '&nbsp;', false);

    $ae = array();
    $sel =& $this->createElement(
        'checkbox',
        'sinfiliacion', 'Incluir', 'Incluir'
    );
    //$sel->setValue(true);
    $ae[] =& $sel;
    $this->addGroup(
        $ae, null, "Víctimas sin " .
        $GLOBALS['etiqueta']['filiacion'],
        '&nbsp;', false
    );

    $ae = array();
    $t =& $this->createElement(
        'radio', 'muestra', 'tabla',
        'Tabla HTML', 'tabla'
    );
    $ae[] =&  $t;

    $ae[] =&  $this->createElement(
        'radio', 'muestra', 'csv',
        'Formato CSV (hoja de cálculo)', 'csv'
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

    $this->setDefaultAction('consulta');

}

}


/**
 * Presenta formulario filtro o estadística
 */
function muestra($dsn, $accno)
{
    $aut_usuario = "";
    autenticaUsuario($dsn, $accno, $aut_usuario, 21);

    $wizard =& new HTML_QuickForm_Controller('EstadisticasInd', false);
    $consweb = new PagEstadisticasIndRot();

    $wizard->addPage($consweb);


    $wizard->addAction('display', new HTML_QuickForm_Action_Display());
    $wizard->addAction('jump', new HTML_QuickForm_Action_Jump());

    $wizard->addAction('process', new AccionEstadisticasIndRot());

    $wizard->run();
}

?>
