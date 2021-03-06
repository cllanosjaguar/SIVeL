<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Ficha para capturar casos (también utilizable para buscar).
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2005 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: captura_caso.php,v 1.89.2.4 2011/10/18 16:05:02 vtamara Exp $
 * @link      http://sivel.sf.net
 * @link      http://www.21st.de/downloads/rapidprototyping.pdf
 */

require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once "confv.php";

require_once 'HTML/QuickForm/Controller.php';
require_once 'HTML/QuickForm/Action/Direct.php';
require_once 'HTML/QuickForm/Action/Display.php';
require_once 'HTML/QuickForm/Action/Next.php';
require_once 'HTML/QuickForm/Action/Back.php';
require_once 'HTML/QuickForm/Action/Jump.php';
require_once 'HTML/QuickForm/header.php';
require_once 'HTML/QuickForm/date.php';
require_once 'HTML/QuickForm/text.php';
require_once 'HTML/QuickForm.php';
require_once 'HTML/QuickForm/Page.php';
require_once 'HTML/CSS.php';

foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
    list($n, $c, $o) = $tab;
    // @codingStandardsIgnoreStart
    require_once "$c.php";
    // @codingStandardsIgnoreEnd
}
require_once 'PagPresentaRes.php';
if (isset($GLOBALS['PagPresentaRes'])) {
    include_once $GLOBALS['PagPresentaRes'] . '.php';
}


/**
 * Presenta formulario.
 * Referencia: pear/lib/HTML/Progress/generator/default.php
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
*/
class PresentaFormulario extends HTML_QuickForm_Action_Display
{

    /**
     * Presenta formulario
     *
     * @param object &$pag Página
     *
     * @return void
     */
    function _renderForm(&$pag)
    {
        $nomPag = $pag->getAttribute('name');
        $css = new HTML_CSS();
        $css->setStyle('body', 'background-color', '#FFFFFF');
        $css->setStyle('body', 'font-family', 'Arial');
        $css->setStyle('body', 'font-size', '10pt');
        $css->setStyle('h1', 'color', '#000FFC');
        $css->setStyle('h1', 'text-align', 'center');
        $css->setStyle('.maintable', 'width', '100%');
        $css->setStyle('.maintable', 'border-width', '0');
        $css->setStyle('.maintable', 'border-color', '#D0D0D0');
        $css->setStyle('.maintable', 'background-color', '#EEE');
        $css->setStyle('th', 'text-align', 'center');
        $css->setStyle('th', 'color', '#FFC');
        $css->setStyle('th', 'background-color', '#AAA');
        $css->setStyle('th', 'white-space', 'nowrap');
        $css->setStyle('input', 'font-family', 'Arial');
        $css->setStyle('input.flat', 'font-size', '10pt');
        $css->setStyle('input.flat', 'border-width', '2px 2px 0px 2px');
        $css->setStyle('input.flat', 'border-color', '#996');
        $enc = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang = "es">
<head>
<meta http-equiv = "Content-Type" content = "text/html; charset=ISO-8859-1">
<title>Ficha caso</title>
<style type = "text/css">
{%style%}
</style>
<script type = "text/javascript" src="sivel.js" type="text/javascript"></script>
<script type = "text/javascript">
<!--
{%javascript%}
-->
</script>
<body>';
        $enc= str_replace('{%style%}', $css->toString(), $enc);
        $js = "";
        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            //echo "OJO n=$n, c=$c, o=$o<br>\n";
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'encJavascript'))) {
                call_user_func(array($c, 'encJavascript'), $js);
            } else {
                echo_enc("Falta encJavascript en $n, $c");
            }
        }
        $js .= '
            function envia(que)
            {
                document.forms[0]._qf_default.value = que;
                document.forms[0].submit();
            }';
        $enc = str_replace('{%javascript%}', $js, $enc);

        $renderer =& $pag->defaultRenderer();

        $renderer->setFormTemplate(
            $enc . '<table class="maintable" ' .
            'align = "left">' .
            '<colgroup><col width = "150" style = "colprin1"/>' .
            '<col/></colgroup>' .
            '<form{attributes}>{content}</form></table>'
        );
        $renderer->setHeaderTemplate('<tr><th colspan = "2">{header}</th></tr>');
        $renderer->setGroupTemplate('<table><tr>{content}</tr></table>', 'name');
        $renderer->setGroupElementTemplate(
            '<td>{element}<br />' .
            '<span class="qfLabel">{label}</span></td>', 'name'
        );
        $renderer->setElementTemplate(
            "\n\t<tr>\n\t\t<td valign=\"top\" " .
            "align=\"left\" colspan=\"2\"><!-- BEGIN error -->" .
            "<span style=\"color: #ff0000\">{error}</span><br />" .
            "<!-- END error -->\t{element}</td>\n\t</tr>", 'tabs'
        );
        $renderer->setElementTemplate(
            "\n\t<tr>\n\t\t<td valign=\"top\" " .
            "align=\"center\" colspan=\"2\"><!-- BEGIN error -->" .
            "<span style=\"color: #ff0000\">{error}</span><br />" .
            "<!-- END error -->\t{element}</td>\n\t</tr>", 'buttons'
        );
        $pag->accept($renderer);

        echo $renderer->toHtml();

        echo "</body></html>";
    }
}


/**
 * Controlador.
 * Con base en pear/lib/HTML/Progress/generator.php
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
*/
class CapturaCaso extends HTML_QuickForm_Controller
{

    var $_botonAnterior  = '<< Anterior';
    var $_botonSiguiente= 'Siguiente >>';
    var $_botonElimina  = 'Elimina caso';
    var $_botonReporte  = 'Val. y Rep. Gen.';
    var $_botonMenu     = 'Menú';
    var $_botonCasoNuevo = 'Caso nuevo';
    var $_botonBusqueda = 'Buscar';
    var $_attrBoton     = array('style'=>'width:85px; padding:0; ');

    var $_tabs;
    var $opciones;

    /**
     * Constructora
     *
     * @param array $opciones Opciones
     *
     * @return void
     */
    function CapturaCaso($opciones)
    {
        $this->opciones = $opciones;
        $this->_modal = false;
        $this->_tabs = $GLOBALS['ficha_tabuladores'];
        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
        ) {
            $ppr = isset($GLOBALS['PagPresentaRes']) ?
                $GLOBALS['PagPresentaRes'] : 'PagPresentaRes';
            $this->_tabs[] = array('presentacion', $ppr,
                'Forma Resultados'
            );
        }

        $nobus = !isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda' ;
        if (!in_array(21, $this->opciones) && $nobus) {
            die($GLOBALS['accno'] . " (3)");
        }
        $mreq = '<span style = "font-size:80%; color:#ff0000;">*</span>' .
            '<span style = "font-size:80%;"> marca un campo requerido</span>';
        foreach ($this->_tabs as $tab) {
            list($pag, $cl) = $tab;
            if (($d = strrpos($cl, "/"))>0) {
                $cl = substr($cl, $d+1);
            }
            if ($pag == "presentacion") {
                $clpag =& new $cl($pag, $opciones);
            } else {
                $clpag =& new $cl($pag);
            }
            $this->addPage($clpag);
            $this->addAction($pag, new Salta());
        }

        $this->addAction('reporte', new ReporteGeneral());
        $this->addAction('menu', new Terminar());
        $this->addAction('elimina_caso', new EliminaCaso());
        $this->addAction('casonuevo', new CasoNuevo());
        $this->addAction('display', new PresentaFormulario());
        $this->addAction('next', new HTML_QuickForm_Action_Next());
        $this->addAction('back', new HTML_QuickForm_Action_Back());
        $this->addAction('jump', new HTML_QuickForm_Action_Jump());

        $this->addAction('process', new Terminar());

        $this->addAction('buscar', new BuscarId());

        // set ProgressBar default values on first run
        $sess = $this->container();
        $defaults = $sess['defaults'];

        if (count($sess['defaults']) == 0) {
            $this->setDefaults(
                array(
                'borderpainted' => false,
                'borderclass'   => 'progressBarBorder',
                'borderstyle'   => array('style' => 'solid',
                    'width' => 0, 'color' => '#000000'
                ),
                'cellid'        => 'progressCell%01s',
                'cellclass'     => 'cell',
                'cellvalue'     => array('min' => 0, 'max' => 100, 'inc' => 1),
                'cellsize'      => array('width' => 15, 'height' => 20,
                    'spacing' => 2, 'count' => 10
                ),
                'cellcolor'     => array('active' => '#006600',
                    'inactive' => '#CCCCCC'
                ),
                'cellfont'      => array('family' => 'Courier, Verdana',
                    'size' => 8, 'color' => '#000000'
                ),
                'stringpainted' => false,
                'stringid'      => 'installationProgress',
                'stringsize'    => array('width' => 50, 'height' => '',
                    'bgcolor' => '#FFFFFF'
                ),
                'stringvalign'  => 'right',
                'stringalign'   => 'right',
                'stringfont'    => array('family' =>
                    'Verdana, Arial, Helvetica, sans-serif',
                    'size' => 10, 'color' => '#000000'
                ),
                'phpcss'        => array('P'=>true)
            )
            );
        }
    }


    /**
     * Crea Tabuladores en Ficha.
     * Adaptada de Progress/generator.php
     *
     * @param object &$page      Página
     * @param array  $attributes Atributos
     *
     * @return void
     */
    function creaTabuladores(&$page, $attributes = null)
    {
        $here = $attributes = HTML_Common::_parseAttributes($attributes);
        $here['disabled'] = 'disabled';
        $pageName = $page->getAttribute('name');
        $jump = array();

        foreach ($this->_tabs as $tab) {
            list($event, $cls) = $tab;
            if (($d = strrpos($cls, "/"))>0) {
                $cls = substr($cls, $d+1);
            }
            $varc = get_class_vars($cls);
            $titulo = isset($GLOBALS['etiqueta'][$cls]) ?
                $GLOBALS['etiqueta'][$cls] : $varc['titulo'];
            $attrs = ($pageName == $event) ? $here : $attributes;
            $jump[] =& $page->createElement(
                'submit',
                $page->getButtonName($event), $titulo,
                HTML_Common::_getAttrString($attrs)
            );
        }
        $page->addGroup($jump, 'tabs', '', '&nbsp;', false);
    }


    /**
     * Agrega botones a una página.
     * Adaptada de Progress/generator.php
     *
     * @param object &$page      HTML_QuicForm_Page página por cambiar
     * @param array  $buttons    Botones por agregar
     * @param array  $attributes Atributos
     *
     * @return void
     */
    function creaBotones(&$page, $buttons, $attributes = null)
    {
        $confirm = $attributes = HTML_Common::_parseAttributes($attributes);
        $confirm['onClick'] = "return(confirm('Are you sure ?'));";

        $prevnext = array();

        foreach ($buttons as $event => $label) {
            if ($event == 'cancel') {
                $type = 'submit';
                $attrs = $confirm;
            } elseif ($event == 'reset') {
                $type = 'reset';
                $attrs = $confirm;
            } else {
                $type = 'submit';
                $attrs = $attributes;
            }
            $prevnext[] =& $page->createElement(
                $type,
                $page->getButtonName($event), $label,
                HTML_Common::_getAttrString($attrs)
            );
        }
        $page->addGroup($prevnext, 'buttons', '', '&nbsp;', false);
    }


    /**
     * Crea botones de la parte inferior de la ficha.
     *
     * @param object &$page HTML_QuickForm_Page Página con ficha.
     *
     * @return void
     */
    function creaBotonesEstandar(&$page)
    {
        $page->addElement('header', null, '&nbsp; ');
        $botones = array('anterior'   => $this->_botonAnterior,
        'reporte'  => $this->_botonReporte,
        'busqueda'=> $this->_botonBusqueda,
        'menu'=> $this->_botonMenu,
        'elimina_caso' => $this->_botonElimina,
        'casonuevo'=> $this->_botonCasoNuevo,
        'siguiente'   => $this->_botonSiguiente
        );
        $this->creaBotones(
            $page, $botones,
            $this->_attrBoton
        );
        if (!isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda'
        ) {
                $this->deshabilitaBotones($page, array('busqueda'));
        } else {
                $this->deshabilitaBotones($page, array('reporte'));
                $this->deshabilitaBotones($page, array('elimina_caso'));
                $this->deshabilitaBotones($page, array('casonuevo'));
        }
    }



    /**
     * Habilita botones estándar de la página.
     * Adaptada de Progress/generator.php
     *
     * @param object &$page  HTML_QuickForm_Page Página con ficha.
     * @param array  $events Eventos
     *
     * @return void
     */
    function habilitaBotones(&$page, $events = array())
    {
        if (!is_a($page, 'HTML_QuickForm_Page')) {
            die("page no es HTML_QuickForm_page");
        } elseif (!is_array($events)) {
            die("events no es arreglo");
        }
        static $all;
        if (!isset($all)) {
            $all = array('back','next','cancel','reset','apply','help');
        }
        $buttons = (count($events) == 0) ? $all : $events;

        foreach ($buttons as $event) {
            $group    =& $page->getElement('buttons');
            $elements =& $group->getElements();
            foreach (array_keys($elements) as $key) {
                if ($group->getElementName($key) == $page->getButtonName($event)) {
                    $elements[$key]->updateAttributes(array('disabled'=>'false'));
                }
            }
        }
    }


    /**
     * Deshabilita los botones estándar de una página.
     * Adaptada de Progress/generator.php
     *
     * @param object &$page  HTML_QuickForm_Page Página con ficha.
     * @param array  $events Eventos
     *
     * @return void
     */
    function deshabilitaBotones(&$page, $events = array())
    {
        if (!is_a($page, 'HTML_QuickForm_Page')) {
            die("page no es HTML_QuickForm_page");
        } elseif (!is_array($events)) {
            die("events no es arreglo");
        }
        static $all;
        if (!isset($all)) {
            $all = array('back','next','cancel','reset','apply','help');
        }
        $buttons = (count($events) == 0) ? $all : $events;

        foreach ($buttons as $event) {
            $group    =& $page->getElement('buttons');
            $elements =& $group->getElements();
            foreach (array_keys($elements) as $key) {
                if ($group->getElementName($key) == $page->getButtonName($event)) {
                    $elements[$key]->updateAttributes(
                        array('disabled'=>'true')
                    );
                }
            }
        }
    }


}

global $dsn;
if (!isset($dsn)) {
    include_once $_SESSION['dirsitio'] . "/conf.php";
}
$aut_usuario = "";
$accno = "";
autenticaUsuario($dsn, $accno, $aut_usuario, 31);

if (isset($_GET['limpia']) && $_GET['limpia'] == 1) {
    unset_var_session();
}

//ambiente();
//die("abc");

$opciones = array();

$nv = "_auth_".nomSesion();
if (isset($_SESSION[$nv]['username']) || $opciones == array()) {
    $d = objeto_tabla('caso');
    if (PEAR::isError($d)) {
        die($d->getMessage());
    }
    $db =& $d->getDatabaseConnection();
    $rol = "";
    sacaOpciones($_SESSION[$nv]['username'], $db, $opciones, $rol);
}

$captura=& new CapturaCaso($opciones);

if (isset($_REQUEST['modo'])) {
    $_SESSION['forma_modo'] = 'consulta';
}

$GLOBALS['ya_captura_caso'] =& $captura;

foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
    list($n, $c, $o) = $tab;
    //echo "OJO n=$n, c=$c, o=$o<br>\n";
    if (($d = strrpos($c, "/"))>0) {
        $c = substr($c, $d+1);
    }
    if (is_callable(array($c, 'iniCaptura'))) {
        call_user_func(array($c, 'iniCaptura'), $opciones);
    } else {
        echo_enc("Falta iniCaptura en $n, $c");
    }
}

$captura->run();
?>
