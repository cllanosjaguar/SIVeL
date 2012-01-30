<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
* Inserci�n de datos en tablas b�sicas
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2006 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: insbasicas.php,v 1.10.2.2 2011/10/11 16:33:37 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Inserci�n de datos en tablas b�sicas
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

/**
 * Inserta data en tabla b�sica
 *
 * @param string $tabla     Nombre de la tabla
 * @param string $llave_sec Llave secundaria
 * @param string $valor     Valor
 * @param string $campos    Campos
 *
 * @return 0 si inserta, 1 si no inserta
 */
function instablabasica($tabla, $llave_sec, $valor, $campos = array())
{
    global $db, $dsn, $mreq, $accno;

    echo "++ Inserci�n en tabla $tabla\n";
    echo "db = $db, dsn = $dsn, mreq = $mreq, accno = $accno\n";

    $na = (int)$db->getOne("SELECT COUNT($llave_sec) FROM $tabla;");

    echo "na = $na\n";

    $_REQUEST = $_POST = $_GET = array();

    $_REQUEST['tabla'] = $_GET['tabla'] = $tabla;
    $_REQUEST["_qf__dataobjects_$tabla"] = $_POST["_qf__dataobjects_$tabla"] =
        '';
    $_REQUEST['id'] = $_POST['id'] = '';
    $_REQUEST[$llave_sec] = $_POST[$llave_sec] = $valor;
    $fc = array('d' => date('d'), 'M' => date('m'), 'Y' => date('Y'));
    $_REQUEST['fechacreacion'] = $_POST['fechacreacion'] = $fc;
    foreach ($campos as $c => $v) {
        $_REQUEST[$c] = $_POST[$c] = $v;
    }

    $_REQUEST['a�adir'] = $_POST['a�adir'] = 'A�adir';

    $_REQUEST['evita_csrf'] = $_SESSION['sin_csrf'] =
        $_POST['evita_csrf'] = 1234;


    include "detalle.php";

    /* Verificando */
    hace_consulta($db, "SELECT COUNT($llave_sec) FROM $tabla;");
    $nd = (int)$db->getOne("SELECT COUNT($llave_sec) FROM $tabla;");
    echo "nd = $nd\n";
    if (($nd-$na) != 1) {
        echo "** No insert� en $tabla\n";
        return 1;
    }
    return 0;
}


$fc = array('d' => date('d'), 'M' => date('m'), 'Y' => date('Y'));
$na = (int)$db->getOne("SELECT COUNT(nombre) FROM departamento;");

$_REQUEST['tabla'] = $_GET['tabla'] = 'departamento';
$_REQUEST['_qf__dataobjects_departamento'] = '';
$_POST['_qf__dataobjects_departamento'] = '';
$_REQUEST['id'] = $_POST['id'] = '';
$_REQUEST['nombre'] = $_POST['nombre'] = 'x';
$_REQUEST['fechacreacion'] = $_POST['fechacreacion'] = $fc;
$_REQUEST['a�adir'] = $_POST['a�adir'] = 'A�adir';

$_REQUEST['evita_csrf'] = $_SESSION['sin_csrf'] = $_POST['evita_csrf'] = 1234;

require "detalle.php";

/* Verificando */
hace_consulta($db, "SELECT COUNT(nombre) FROM departamento;");
$nd = (int)$db->getOne("SELECT COUNT(nombre) FROM departamento;");

if (($nd-$na) != 1) {
    echo "No insert�";
    exit(1);
}

$c = 0;

foreach (array('tipo_sitio', 'frontera', 'region', 'filiacion', 'organizacion',
    'profesion', 'sector_social', 'vinculo_estado', 'antecedente',
    'contexto'
) as $t
) {
    $c += instablabasica($t, 'nombre', $t . '1');
}

$c += instablabasica(
    'intervalo', 'nombre', 'intervalo1',
    array('rango' => '0 a 100')
);

$c += instablabasica(
    'intervalo', 'nombre', 'SIN INFORMACI�N',
    array('rango' => 'SIN INFORMACI�N')
);

$c += instablabasica(
    'prensa', 'nombre', 'prensa1',
    array('tipo_fuente' => 'Indirecta')
);

$c += instablabasica(
    'presuntos_responsables', 'nombre', 'presuntos r1',
    array('polo' => 'OTROS', 'fechacreacion' => $fc)
);

$c += instablabasica(
    'rango_edad', 'nombre', 'rango_edad1',
    array('rango' => 'desc1', 'limiteinferior' => '0',
    'limitesuperior' => '100'
    )
);

$c += instablabasica(
    'parametros_reporte_consolidado', 'rotulo', 'rotulo1',
    array('tipo_violencia' => 'UNA',
    'clasificacion' => 'OTRA'
    )
);

$c += instablabasica(
    'tipo_violencia', 'nombre', 'tipo1',
    array('id' => 'T', 'nomcorto' => 't1', 'fechacreacion' => $fc)
);
$c += instablabasica(
    'supracategoria', 'nombre', 'supra1',
    array('id_tipo_violencia' => 'T', 'id' => 1000, 'fechacreacion' => $fc)
);
$c += instablabasica(
    'categoria', 'nombre', 'cat1',
    array('id_supracategoria' => 'T:1000',
        'id' => 1000,
        'col_rep_consolidado' => 1,
        'tipocat' => 'I',
        'fechacreacion' => $fc
    )
);
$c += instablabasica(
    'municipio', 'nombre', 'municipio1',
    array('id_departamento' => '1' )
);
$c += instablabasica(
    'clase', 'nombre', 'clase1',
    array('id_municipio' => '1-1' )
);

if ($c > 0) {
    echo "** Errores: $c\n";
}

exit($c);
?>
