<?php

/*

 * @Nombre    : Fechas
 * @Author    : Erick Rodriguez
 * @Copyright : Erick Rodriguez
 * @Creado el : 05-nov-2019, 09:38:29 AM
 */

/**
 * Description of Fechas
 *
 * @author Erick
 */

namespace Application\Extras\Utilidades;

class Fechas {
    //put your code here

    /**
     * Convierte una fecha al formato de base de datos.
     * 
     * @param string $fecha Fecha que desea convertir.
     * @param string $formato Formato de la fecha default "d-m-Y".<br>
     * @return string Año <br>
     * ej. 03-10-2019 -> 2019-10-03.
     */
    public function convertirFechaToDB($fecha, $formato = "d-m-Y") {
        $info_fecha = \date_parse_from_format($formato, $fecha);


        return "{$this->unoADosDigitos($info_fecha['year'])}-{$this->unoADosDigitos($info_fecha['month'])}-{$this->unoADosDigitos($info_fecha['day'])}";
    }

    /**
     * Devuelve el año de la fecha.
     * 
     * @param string $fecha Fecha que desea obtener el año.
     * @param string $formato Formato de la fecha default "Y-m-d".<br>
     * @return string Año <br>
     * ej. 2019-10-03 -> 2019.
     */
    public function getAnio($fecha, $formato = "Y-m-d") {
        $info_fecha = \date_parse_from_format($formato, $fecha);
        return $this->unoADosDigitos($info_fecha['year']);
    }

    /**
     * Devuelve el mes de la fecha.
     * 
     * @param string $fecha Fecha que desea obtener el mes.
     * @param string $formato Formato de la fecha default "Y-m-d".<br>
     * @return string Mes <br>
     * ej. 2019-10-03 -> 10.
     */
    public function getMes($fecha, $formato = "Y-m-d") {
        $info_fecha = \date_parse_from_format($formato, $fecha);
        return $this->unoADosDigitos($info_fecha['month']);
    }

    /**
     * Devuelve el día de la fecha.
     * 
     * @param string $fecha Fecha que desea obtener el día.
     * @param string $formato Formato de la fecha default "Y-m-d".<br>
     * @return string Día <br>
     * ej. 2019-10-03 -> 03.
     */
    public function getDia($fecha, $formato = "Y-m-d") {
        $info_fecha = \date_parse_from_format($formato, $fecha);
        return $this->unoADosDigitos($info_fecha['day']);
    }

    /**
     * Convierte en número de mes en el nombre del mes en formato corto.<br>
     * 
     * @param string $mes Número de mes.
     * @return string Nombre del mes en texto corto<br>
     * ej. 1 | 01 -> ene.  
     */
    public function mesToTextCorto($mes) {
        $meses = array('1' => 'ene', '2' => 'feb', '3' => 'mar', '4' => 'abr', '5' => 'may', '6' => 'jun', '7' => 'jul', '8' => 'ago', '9' => 'sep', '01' => 'ene', '02' => 'feb', '03' => 'mar', '04' => 'abr', '05' => 'may', '06' => 'jun', '07' => 'jul', '08' => 'ago', '09' => 'sep', '10' => 'oct', '11' => 'nov', '12' => 'dic');
        return $meses[$mes];
    }

    /**
     * Convierte en número de mes en el nombre del mes en formato largo.<br>
     * 
     * @param string $mes Número de mes.
     * @return string Nombre del mes en texto largo.<br>
     * ej. 1 | 01 -> enero.  
     */
    public function mesToTextLargo($mes) {
        $meses = array('1' => 'enero', '2' => 'febrero', '3' => 'marzo', '4' => 'abril', '5' => 'mayo', '6' => 'junio', '7' => 'julio', '8' => 'agosto', '9' => 'septiembre',
            '01' => 'enero', '02' => 'febrero', '03' => 'marzo', '04' => 'abril', '05' => 'mayo', '06' => 'junio', '07' => 'julio', '08' => 'agosto', '09' => 'septiembre', '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre');
        return $meses[$mes];
    }

    /**
     * Convierte una fecha en texto mixto.
     * 
     * @param string $fecha Fecha que desea convertir a texto.
     * @param string $formato Formato de la fecha default "Y-m-d".<br>
     * @param boolean $mesCorto Cambiar a formato del mes corto default false.
     * @return string Fecha en formato mixto.<br>
     * ej. 2019-10-03 -> 03 de octubre de 2019.
     */
    public function fecha2TextoMixto($fecha, $formato = "Y-m-d", $mesCorto = false) {
        //Cambiar formatos de fechas

        $info_fecha = \date_parse_from_format($formato, $fecha);

        $mesTxt = $this->mesToTextLargo($info_fecha['month']);
        if ($mesCorto) {
            $mesTxt = $this->mesToTextCorto($info_fecha['month']);
        }

        $fechaTxt = "{$this->unoADosDigitos($info_fecha['day'])} de {$mesTxt} de {$info_fecha['year']}";
        return $fechaTxt;
    }

    /**
     * Convierte una fecha en texto completo.
     * 
     * @param string $fecha Fecha que desea convertir a texto.
     * @param string $formato Formato de la fecha default "Y-m-d".<br>
     * @param boolean $mesCorto Cambiar a formato del mes corto default false.
     * @return string Fecha en formato completo.<br>
     * ej. 2019-10-03 -> tres de octubre de año 2019.
     */
    public function fecha2TextoCompleto($fecha, $formato = "Y-m-d", $mesCorto = false) {
        //Cambiar formatos de fechas

        $info_fecha = \date_parse_from_format($formato, $fecha);
        $numeros = new Numeros();
        $mesTxt = $this->mesToTextLargo($info_fecha['month']);
        if ($mesCorto) {
            $mesTxt = $this->mesToTextCorto($info_fecha['month']);
        }

        $fechaTxt = "{$numeros->numerosEnLetrasFecha($this->unoADosDigitos($info_fecha['day']))} días de {$mesTxt} de {$info_fecha['year']}";
        return $fechaTxt;
    }

    /**
     * Convierte una fecha en texto completo incluyendo el año.
     * 
     * @param string $fecha Fecha que desea convertir a texto.
     * @param string $formato Formato de la fecha default "Y-m-d".<br>
     * @param boolean $mesCorto Cambiar a formato del mes corto default false.
     * @return string Fecha en formato completo.<br>
     * ej. 2019-10-03 -> tres de octubre de dos mil diecinueve.
     */
    public function fecha2TextoCompletoFull($fecha, $formato = "Y-m-d", $mesCorto = false) {
        //Cambiar formatos de fechas

        $info_fecha = \date_parse_from_format($formato, $fecha);
        $numeros = new Numeros();
        $mesTxt = $this->mesToTextLargo($info_fecha['month']);
        if ($mesCorto) {
            $mesTxt = $this->mesToTextCorto($info_fecha['month']);
        }

        $fechaTxt = "{$numeros->numerosEnLetrasFecha($this->unoADosDigitos($info_fecha['day']))} días de {$mesTxt} de {$numeros->numerosEnLetrasFecha($info_fecha['year'])}";
        return $fechaTxt;
    }

    /**
     * Extrae la hora de una dato tipo DateTime
     * 
     * @param string $fecha (DateTime) Fecha para extraer la hora.<br>
     * @param string $formato Formato del parámetro $fecha, default "Y-m-d H:i:s"<br>
     * @return string La hora contenida en la fecha en formato H:m:s<br>
     * eje. 2019-10-01 15:01:23 -> 15:01:23
     */
    public function fecha2Hora($fecha, $formato = "Y-m-d H:i:s") {
        //Cambiar formatos de fechas
        $info_fecha = \date_parse_from_format($formato, $fecha);
        $fechaTxt = "{$info_fecha['hour']}:{$info_fecha['minute']}:{$info_fecha['second']}";
        return $fechaTxt;
    }

    /**
     * Pasa de un dígito a dos dígitos.
     * 
     * @param string $numero Número en un dígito.
     * @return string Número en dos dígitos.<br>
     * ej. 1 -> 01
     */
    public function unoADosDigitos($numero) {
        $digitos = array('0' => '00', '1' => '01', '2' => '02', '3' => '03', '4' => '04', '5' => '05', '6' => '06', '7' => '07', '8' => '08', '9' => '09',
            '00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09',
            '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19',
            '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29',
            '30' => '30', '31' => '31', '32' => '32', '33' => '33', '34' => '34', '35' => '35', '36' => '36', '37' => '37', '38' => '38', '39' => '39',
            '40' => '40', '41' => '41', '42' => '42', '43' => '43', '44' => '44', '45' => '45', '46' => '46', '47' => '47', '48' => '48', '49' => '49',
            '50' => '50', '51' => '51', '52' => '52', '53' => '53', '54' => '54', '55' => '55', '56' => '56', '57' => '57', '58' => '58', '59' => '59'
        );

        if (\array_key_exists($numero, $digitos)) {
            return $digitos[$numero];
        } else {
            return $numero;
        }
    }

    /**
     * Recibe el mes y el año para determinar cual el ultimo día del mes tomando en cuenta los años bisiestos.
     * @param type $mes
     * @param type $anio
     * @return string
     */
    public function getUltimoDíaDelMes($mes, $anio) {
        $mes = $this->unoADosDigitos($mes);
        $diaMax = "30";
        if ($mes == 2) {
            $diaMax = "28";
            if ($this->esBisiesto($anio)) {
                $diaMax = "29";
            }
        }

        return $diaMax;
    }

    private function esBisiesto($anio) {
        if (($anio % 4 == 0 && $anio % 100 != 0) || $anio % 400 == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Recibe dos fechas para determinar cuantos días calendario hay en formato d-m-Y.
     * @param type $fecha_inicial
     * @param type $fecha_final
     * @return int
     */
    public function getDiasCalendario($fecha_inicial, $fecha_final) {
        $t_inicio = \strtotime($this->convertirFechaToDB($fecha_inicial));
        $t_final = \strtotime($this->convertirFechaToDB($fecha_final));

        if ($t_final < $t_inicio) {
            return -1;
        } else {
            return (($t_final - $t_inicio) / 86400) + 1;
        }
    }

    /**
     * Recibe la fecha de inicio y los días para determinar cuando es la fecha final d-m-Y.
     * @param string $fecha_inicial
     * @param int $dias
     * @return array[]
     */
    public function getFechaFinal($fecha_inicial, $dias) {
        $t_inicio = \strtotime($this->convertirFechaToDB($fecha_inicial));
        $t_final = (86400 * ($dias - 1)) + $t_inicio;


        $fecha_final = \date('Y-m-d', $t_final);
        $fecha_final_p = \date('d-m-Y', $t_final);
        $fecha_final_txt = $this->fecha2TextoMixto($fecha_final);

        $res['fecha'] = $fecha_final;
        $res['fecha_p'] = $fecha_final_p;
        $res['fecha_txt'] = $fecha_final_txt;

        return $res;
    }

    public function getTiempoEnTexto($fecha_inicial, $fecha_final) {

        $numeros_utilidad = new Numeros();

        $date_ini = new \DateTime($fecha_inicial);
        $time_fin = \strtotime($fecha_final) + 86400;
        $fecha_fin = \date('Y-m-d', $time_fin);
        $date_fin = new \DateTime($fecha_fin);
        $diferencia = $date_ini->diff($date_fin);
        $anios = $diferencia->y;
        $meses = $diferencia->m;
        $dias = $diferencia->d;

        $texto_dias = "ERROR DE FECHAS";

        if ($anios > 0 && $meses > 0 && $dias > 0) {
            $anios_letras = $numeros_utilidad->numerosEnLetrasFecha($anios);
            $meses_letras = $numeros_utilidad->numerosEnLetrasFecha($meses);
            $dias_letras = $numeros_utilidad->numerosEnLetrasFecha($dias);

            $texto_dias = "$anios_letras ($anios) años con $meses_letras ($meses) meses y $dias_letras (" . ($dias) . ") días ";
        } else if ($anios > 0 && $meses == 0 && $dias > 0) {
            $anios_letras = $numeros_utilidad->numerosEnLetrasFecha($anios);
            $dias_letras = $numeros_utilidad->numerosEnLetrasFecha($dias);

            $texto_dias = "$anios_letras ($anios) años y $dias_letras (" . ($dias) . ") días ";
        } else if ($anios > 0 && $meses > 0 && $dias == 0) {
            $anios_letras = $numeros_utilidad->numerosEnLetrasFecha($anios);
            $meses_letras = $numeros_utilidad->numerosEnLetrasFecha($meses);

            $texto_dias = "$anios_letras ($anios) años y $meses_letras ($meses) meses ";
        } else if ($anios > 0 && $meses == 0 && $dias == 0) {
            $anios_letras = $numeros_utilidad->numerosEnLetrasFecha($anios);

            $texto_dias = "$anios_letras ($anios) años exactos ";
        } else if ($anios == 0 && $meses > 0 && $dias > 0) {
            $meses_letras = $numeros_utilidad->numerosEnLetrasFecha($meses);
            $dias_letras = $numeros_utilidad->numerosEnLetrasFecha($dias);

            $texto_dias = "$meses_letras ($meses) meses y $dias_letras (" . ($dias) . ") días ";
        } else if ($anios == 0 && $meses > 0 && $dias == 0) {
            $meses_letras = $numeros_utilidad->numerosEnLetrasFecha($meses);

            $texto_dias = "$meses_letras ($meses) meses ";
        } else if ($anios == 0 && $meses == 0 && $dias > 0) {
            $dias_letras = $numeros_utilidad->numerosEnLetrasFecha($dias);

            $texto_dias = "$dias_letras (" . ($dias) . ") días ";
        }
        
        return $texto_dias;
    }

    /**
     * Recibe fechas para determinar el días de la semana, en formato Y-m-d.
     * @param type $fecha
     * @return string
     */
    public function getDiaEnTexto($fecha) {

        $t_f = \strtotime($fecha);

        return $this->num2dia(\date('w', $t_f));
    }

    /**
     * Recibe un día en formato numerico.
     * @param int $num
     * @return string
     */
    private function num2dia($num) {
        $dias = ['0' => "Domingo", '1' => "Lunes", '2' => "Martes", '3' => "Miércoles", '4' => "Jueves", '5' => "Viernes", '6' => "Sábado"];
        return $dias[$num];
    }

}
