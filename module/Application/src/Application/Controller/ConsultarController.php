<?php

/*

 * @Nombre    : ConsultarController
 * @Author    : Erick Rodriguez
 * @Copyright : Erick Rodriguez
 * @Creado el : 02-sep-2021, 09:15:48 AM
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/* Aquí los Modelos */
use Application\Model\CnePersonasModel;
use Application\Extras\Utilidades\Fechas;

class ConsultarController extends AbstractActionController {

    public function __construct() {
        /* $_SESSION['unidades'] = 'active'; */
    }

    public function consultarAction() {

        if ($this->getRequest()->isXmlHttpRequest()) {
            //para AJAX
            exit;
        } else if ($this->getRequest()->isPost()) {
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . "/");
        } else {
            //Normal GET
            $vista = new ViewModel([]); //Instancia de la vista
            $this->layout(); //Parametro pasado al layout Titulo de la página
            return $vista;
        }
    }

    public function getdatosserviceAction() {
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
        $cne_personas_model = new CnePersonasModel($this->dbAdapter);

        $utilidad_fechas = new Fechas();

        $datosFormularios = $this->request->getPost()->toArray();

        if (!isset($datosFormularios['identidad'])) {
            $r['resp'] = "error";
            $r['mensaje'] = "Se esperaba un argumento.";
            $r['info'] = "";
            echo \json_encode($r);
            exit;
        }

        $identidad = $datosFormularios['identidad'];


        $patron_1 = "/^[0-9]{4}[-][0-9]{4}[-][0-9]{5}$/";
        $patron_2 = "/^[0-9]{4}[ ][0-9]{4}[ ][0-9]{5}$/";
        $patron_3 = "/^[0-9]{13}$/";

        if (!\preg_match($patron_1, $identidad) && !\preg_match($patron_2, $identidad) && !\preg_match($patron_3, $identidad)) {
            $r['resp'] = "errorf";
            $r['mensaje'] = "La identidad N° $identidad no corresponde a un número correcto, se esperaba 0801-1950-12345, 0801 1950 12345, 0801195012345.";
            $r['info'] = "";
            echo \json_encode($r);
            exit;
        }

        $info_persona = $cne_personas_model->getInfoPersona_cne($identidad);

        //Agregar los guiones a la identidad para la presentación
        $id = $this->agregarGuionesIdentidad($identidad);

        //En caso de no existir el número de identidad
        if (!$info_persona) {
            $r['resp'] = "no encontrado";
            $r['mensaje'] = "La identidad N° $id no  fue encontrada, llenar datos manualmente.";
            $r['info'] = "";
            echo \json_encode($r);
            exit;
        }

        $nombre_completo = $info_persona['nombres'] . " " . $info_persona['apellidos'];
        
        

        $direccion = $info_persona['nombre_lugar_poblado'] . ", " . $info_persona['nombre_municipio'] . ", " . $info_persona['nombre_departamento'];

        $info_persona['direccion'] = $direccion;
        $info_persona['numero_identidad'] = $id;
//        $info_persona['fecha_nacimiento_f'] = $fecha_nacimiento_f;
        $info_persona['nombre_completo'] = $nombre_completo;
        
        $r['resp'] = "ok";
        $r['d'] = $info_persona;
        
        header('content-Type: text/html; charset=utf-8');  
        
        echo \json_encode($r);

        exit;
    }

    public function getdatoslocalAction() {

        if ($this->getRequest()->isXmlHttpRequest()) {

            $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter');
            $cne_personas_model = new CnePersonasModel($this->dbAdapter);

            $utilidad_fechas = new Fechas();

            $datosFormularios = $this->request->getPost()->toArray();

            $identidad = $datosFormularios['identidad'];

            $info_persona = $cne_personas_model->getInfoPersona_cne($identidad);

            //Agregar los guiones a la identidad para la presentación
            $id = $this->agregarGuionesIdentidad($identidad);

            $patron_1 = "/^[0-9]{4}[-][0-9]{4}[-][0-9]{5}$/";
            $patron_2 = "/^[0-9]{4}[ ][0-9]{4}[ ][0-9]{5}$/";
            $patron_3 = "/^[0-9]{13}$/";

            if (!\preg_match($patron_1, $identidad) && !\preg_match($patron_2, $identidad) && !\preg_match($patron_3, $identidad)) {
                $r = "<div class=\"alert alert-danger\" role=\"alert\" style=\"font-size: 20px;\"><span style=\"font-size: 25px;\">La identidad tiene un formato incorrecto.</span><br>Formatos admitidos:\n\<br>Con guiones <b>0801-1950-12345</b><br>Con espacios <b>0801 1950 12345</b><br>Sin guiones <b>0801195012345</b></div>";

                echo $r;
                exit;
            }

            //En caso de no existir el número de identidad
            if (!$info_persona) {
                $r = "<div class=\"alert alert-danger\" role=\"alert\" style=\"font-size: 20px;\"><span style=\"font-size: 25px;\">La identidad <b>N° $id</b> no pudo ser encontrada.</span></div>";

                echo $r;
                exit;
            }


            $nombre_completo = $info_persona['nombres'] . " " . $info_persona['apellidos'];
            $genero = $info_persona['descripcion_sexo'];
            $fecha_nacimiento_f = "";
            if ($info_persona) {
                $fecha_nacimiento_f = $utilidad_fechas->fecha2TextoMixto($info_persona['fecha_nacimiento_f'], "d/m/Y");
            }

            $direccion = $info_persona['nombre_lugar_poblado'] . ", " . $info_persona['nombre_municipio'] . ", " . $info_persona['nombre_departamento'];
            $centro_votacion = $info_persona['centro_votacion'];
            $sector_votacion = $info_persona['nombre_sector_electoral'];
            $mesa_votacion = $info_persona['numero_mesa'];
            $linea_votacion = $info_persona['numero_linea'];
            $estado_votacion = $info_persona['desc_habil_inhabil'];


            $respuesta = "<div id=\"_imprimir\">"
                    . "<div class=\"panel panel-success\">"
                    . "<div class=\"panel-heading\">"
                    . "<h3 class=\"panel-title\">Información del Centro de Votación</h3>"
                    . "</div>"
                    . "<div class=\"panel-body\">"
                    . "<table class=\"table\">"
                    . "<tr>"
                    . "<th colspan=\"2\" style=\"text-align: center;\">INFORMACIÓN PERSONAL</th>"
                    . "</tr>"
                    . "<tr>"
                    . "<td style\"width=40%;\"><b>Nombre Completo</b></td><td colspan=\"2\" style\"width=60%\">$nombre_completo</td>"
                    . "</tr>"
                    . "<tr>"
                    . "<td style\"width=40%;\"><b>N° Identidadd</b></td><td colspan=\"2\" style\"width=60%\">$identidad</td>"
                    . "</tr>"
                    . "<tr>"
                    . "<td style\"width=40%;\"><b>Genero</b></td><td style\"width=60%\">$genero</td>"
                    . "</tr>"
                    . "<tr>"
                    . "<td style\"width=40%\"><b>Fecha de Nacimiento</b></td><td style\"width=60%\">$fecha_nacimiento_f</td>"
                    . "</tr>"
                    . "<tr>"
                    . "<td style\"width=40%\"><b>Dirección</b></td><td colspan=\"2\" style\"width=60%\">$direccion</td>"
                    . "</tr>"
                    . "<tr>"
                    . "<td colspan=\"2\" style=\"text-align: center;\">INFORMACIÓN DEL CENTRO VOTACIÓN</th>"
                    . "</tr>"
                    . "<tr>"
                    . "<td style\"width=40%\"><b>Centro de Votación</b></td><td colspan=\"2\" style\"width=60%\">$centro_votacion</td>"
                    . "</tr>"
                    . "<tr>"
                    . "<td style\"width=40%\"><b>Sector de Votación</b></td><td colspan=\"2\" style\"width=60%\">$sector_votacion</td>"
                    . "</tr>"
                    . "<tr>"
                    . "<td style\"width=40%\"><b>Mesa de Votación</b></td><td colspan=\"2\" style\"width=60%\">$mesa_votacion</td>"
                    . "</tr>"
                    . "<tr>"
                    . "<td style\"width=40%\"><b>Línea</b></td><td colspan=\"2\" style\"width=60%\">$linea_votacion</td>"
                    . "</tr>"
                    . "<tr>"
                    . "<td style\"width=40%\"><b>Estado</b></td><td colspan=\"2\" style\"width=60%\">$estado_votacion</td>"
                    . "</tr>"
                    . "</table>"
                    . "<hr>"
                    . "</div>"
                    . "</div>"
                    . "</div>"
                    . "<a href=\"javascript:imprimirDiv()\" type=\"button\" id=\"btn_imprimir\" class=\"btn btn-primary  next \" ><span class=\"glyphicon glyphicon-print\"></span> Imprimir </a>";

            echo $respuesta;
            exit;
        } else if ($this->getRequest()->isPost()) {
            exit;
        } else {
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . "/");
        }
    }

    private function agregarGuionesIdentidad($identidad) {
        $id = \str_replace(["-", " "], "", $identidad);

        $p1 = \substr($id, 0, 4);    // devuelve los primeros 4
        $p2 = \substr($id, 4, 4);    // devuelve los 4 del año
        $p3 = \substr($id, -5); // devuelve los últimos 5

        return "$p1-$p2-$p3";
    }

}