<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
* Actualiza modulo etiquetas
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2009 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   $$
 * @link      http://sivel.sf.net
 */

/** Actualiza base de datos despu�s de actualizar fuentes */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once "confv.php";
require_once "misc.php";
require_once "DataObjects/Categoria.php";
require_once "misc_actualiza.php";


$aut_usuario = "";
$db = autenticaUsuario($dsn, $accno, $aut_usuario, 21);


$act = objeto_tabla('Actualizacion_base');

$idac = 'eti-1';
if (!aplicado($idac)) {
    $r = hace_consulta($db, "CREATE SEQUENCE etiqueta_seq", false);
    $r = hace_consulta(
        $db, "CREATE TABLE etiqueta (
        id      INTEGER PRIMARY KEY DEFAULT (nextval('etiqueta_seq')),
        nombre VARCHAR(50) NOT NULL,
        observaciones VARCHAR(200) NOT NULL
    )", false
);
    $r = hace_consulta(
        $db, "CREATE TABLE etiquetacaso (
        id_caso      INTEGER REFERENCES caso NOT NULL,
        id_etiqueta       INTEGER REFERENCES etiqueta NOT NULL,
        id_funcionario INTEGER REFERENCES funcionario NOT NULL,
        fecha   DATE NOT NULL,
        observaciones VARCHAR(5000),
        PRIMARY KEY (id_caso, id_etiqueta,  id_funcionario, fecha)
    );", false
);

    aplicaact($act, $idac, 'Tablas para etiquetas');
}

$idac = 'eti-c1';
if (aplicado('obs-1') && !aplicado($idac)) {
    $r = hace_consulta(
        $db, "INSERT INTO etiqueta (id, nombre, observaciones)" .
        " SELECT id, nombre, observaciones FROM estado", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO etiquetacaso (id_caso, id_etiqueta, " .
        " id_funcionario, fecha, observaciones) SELECT id_caso, id_estado, " .
        " id_funcionario, fecha, observaciones FROM estadocaso", false
    );
    /*$r = hace_consulta($db, "DROP TABLE estadocaso", false);
    $r = hace_consulta($db, "DROP TABLE estado", false); */

    aplicaact($act, $idac, 'Etiquetas iniciales');
}


$idac = 'eti-d1';
if (!aplicado($idac)) {
    $r = hace_consulta(
        $db, "INSERT INTO etiqueta (nombre, observaciones) " .
        " VALUES ('SINCODH:PUBLICO', 'El documento es p�blico')", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO etiqueta (nombre, observaciones) " .
        " VALUES ('SINCODH:PRIVADO', 'El documento es privado')", false
    );

    aplicaact($act, $idac, 'Etiquetas SINCODH');
}

/**
 * Inserta una etiqueta si no esta
 *
 * @param objetc &$db         Conexi�n a base de datos
 * @param string $etiqueta    Etiqueta por insertar
 * @param string $descripcion Descripci�n de etiqueta
 *
 * @return void
 */
function inserta_etiqueta_si_falta(&$db, $etiqueta, $descripcion)
{
    $idetiqueta = (int)$db->getOne(
        "SELECT id FROM etiqueta " .
        "WHERE nombre = '$etiqueta'"
    );
    if ($idetiqueta == 0) {
        $etiqueta = var_escapa($etiqueta, $db);
        $descripcion = var_escapa($descripcion, $db);
        hace_consulta(
            $db, "INSERT INTO etiqueta (nombre, observaciones) " .
            " VALUES ('$etiqueta', '$descripcion')", false
        );
    }
}

$idac = 'eti-ir';
if (!aplicado($idac)) {
    inserta_etiqueta_si_falta(
        $db, 'IMPORTA_RELATO', 'Observaciones al importar relato'
    );
    aplicaact($act, $idac, 'Etiqueta para observaciones al importar relato');
}


$idac = 'eti-col';
if (!aplicado($idac)) {
    foreach (array('ROJO' => 'FF0000',
        'VERDE' => '00FF00',
        'AZUL' => '0000FF',
        'AMARILLO' => 'FFFF00') as $col => $rgb
    ) {
        inserta_etiqueta_si_falta(
            $db, $col, "Color #$rgb"
        );
    }
    aplicaact($act, $idac, 'Etiquetas como colores');
}


$idac = 'eti-er';
if (!aplicado($idac)) {
    inserta_etiqueta_si_falta(
        $db, 'ERROR_IMPORTACI�N', 'Error en importaci�n'
    );
    aplicaact($act, $idac, 'Etiqueta para errores de importaci�n');
}

$idac = 'eti-fi';
if (!aplicado($idac)) {
    inserta_etiqueta_si_falta(
        $db, 'MES_INEXACTO', 'Dia y mes del caso son inexactos'
    );
    inserta_etiqueta_si_falta(
        $db, 'DIA_INEXACTO', 'Dia del caso es inexacto'
    );
    aplicaact($act, $idac, 'Etiqueta para casos con fecha inexacta');
}

$idac = 'eti-fe';
if (!aplicado($idac)) {
    agrega_fechas($db, 'etiqueta');

    aplicaact($act, $idac, 'Fecha en tablas b�sicas');
}

echo "Actualizando indices<br>";
actualiza_indice($db, 'etiqueta', 'id', 100);



?>
