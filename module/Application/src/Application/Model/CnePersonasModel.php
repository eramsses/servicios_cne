<?php

/*

 * @Nombre    : CnePersonasModel
 * @Author    : Erick Rodriguez
 * @Copyright : Erick Rodriguez
 * @Creado el : 02-sep-2021, 08:12:03 AM
 */

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;

class CnePersonasModel extends TableGateway {

    private $dbAdapter;

    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
        $this->dbAdapter = $adapter;
        return parent::__construct('cne_personas', $adapter, $databaseSchema, $selectResultPrototype);
    }

    public function getAll() {
        $r = $this->select();
        return $r->toArray();
    }

    public function getPorId($id) {
        $idT = (int) $id;
        $rowset = $this->select(array('id' => $idT));
        $fila = $rowset->current();

        if (!$fila) {
            //throw new \Exception("No hay registros asociados al valor $id");
        }

        return $fila;
    }

    public function agregarNuevo($data = array()) {
        return $this->insert($data);
    }

    public function actualizar($id, $data = array()) {
        return $this->update($data, array('id' => $id));
    }

    public function borrar($id) {
        return $this->delete(array('id' => $id));
    }

    public function getInfoPersona_cne($identidad) {
        
        $id = \str_replace(["-"," "],"",$identidad);
       
        $SQL = "SELECT p.numero_identidad, CONCAT(p.primer_nombre, ' ', p.segundo_nombre) AS nombres, CONCAT(p.primer_apellido, ' ', p.segundo_apellido) AS apellidos, "
                . "DATE_FORMAT(p.fecha_nacimiento,'%d-%m-%Y') as fecha_nacimiento_f, p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido, "
                . "gen.descripcion_sexo, d.nombre_departamento, m.nombre_municipio, a.descripcion_area, s.nombre_sector_electoral, lp.nombre_lugar_poblado, "
                . "chi.desc_habil_inhabil, p.numero_mesa, p.numero_linea, cv.nombre_centro AS centro_votacion, et.descripcion AS est_identidad "
                . "FROM cne_personas AS p, cne_generos AS gen, cne_departamentos AS d, cne_municipios AS m, cne_areas AS a, cne_sectores_electorales AS s, "
                . "cne_lugar_poblado AS lp, cne_codigos_habi_inha AS chi, cne_centros_de_votacion AS cv, cne_estatus_identidad AS et "
                . "WHERE p.numero_identidad = '$id' "
                . "AND gen.codigo_sexo = p.codigo_sexo "
                . "AND d.codigo_departamento = p.codigo_departamento_domicilio "
                . "AND m.codigo_departamento = p.codigo_departamento_domicilio "
                . "AND m.codigo_municipio = p.codigo_municipio_domicilio "
                . "AND a.codigo_area = p.codigo_area "
                . "AND s.codigo_departamento = d.codigo_departamento "
                . "AND s.codigo_municipio = p.codigo_municipio_domicilio "
                . "AND s.codigo_area = p.codigo_area "
                . "AND s.codigo_sector_electoral = p.codigo_sector_electoral "
                . "AND lp.codigo_departamento = p.codigo_departamento_domicilio "
                . "AND lp.codigo_municipio = p.codigo_municipio_domicilio "
                . "AND lp.codigo_aldea = p.codigo_aldea "
                . "AND lp.codigo_lugar_poblado = p.codigo_lugar_poblado "
                . "AND chi.codigo_habil_inhabil = p.codigo_habil_inhabil "
                . "AND cv.codigo_departamento = p.codigo_departamento_domicilio "
                . "AND cv.codigo_municipio = p.codigo_municipio_domicilio "
                . "AND cv.codigo_area = p.codigo_area "
                . "AND cv.codigo_sector_electoral = p.codigo_sector_electoral "
                . "AND cv.codigo_centro_votacion = p.codigo_centro_votacion "
                . "AND et.codigo_estado_tarjeta = p.codigo_estado_tarjeta ";
        
        $rs = $this->dbAdapter->query($SQL, Adapter::QUERY_MODE_EXECUTE);

        return $rs->current();
    }

}
