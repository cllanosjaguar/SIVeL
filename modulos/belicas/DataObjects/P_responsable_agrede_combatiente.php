<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Objeto asociado a una tabla p_responsable_agrede_combatiente
 * Parcialmente generado por DB_DataObject.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: P_responsable_agrede_combatiente.php,v 1.6.2.1 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla p_responsable_agrede_combatiente.
 * Ver documentaci�n de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_P_responsable_agrede_combatiente extends DB_DataObject_SIVeL
{
    var $__table = 'p_responsable_agrede_combatiente';    // table name
    var $id_p_responsable;                // int4(4)  multiple_key
    var $id_combatiente;                  // int4(4)  multiple_key

    var $nom_tabla = 'Presuntso Responsable Agrede Combatiente';

    var $fb_hidePrimaryKey = true;
}

?>
