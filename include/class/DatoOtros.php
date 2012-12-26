<?php
class DatoOtros
{
    private $dato_ciu;
    private $dato_ent;
    private $dato_fun;
    private $dato_oem;
    private $conn;
    private $dato_dir_direccion;

    public function DatoOtros($conn)
    {
       $this->conn=$conn;
    }

    public function obtieneDatosReales($codi_dir_drecciones)
    {
        $isql = "select * from sgd_dir_drecciones a where  sgd_dir_codigo = $codi_dir_drecciones";
        $rs =  $this->conn->Execute($isql);
        $ciu = $rs->fields["SGD_CIU_CODIGO"];
        $oem = $rs->fields["SGD_OEM_CODIGO"];
        $esp = $rs->fields["SGD_ESP_CODI"];
        $fun = $rs->fields["SGD_DOC_FUN"];
        $nom = $rs->fields["SGD_DIR_NOMBRE"];
        //if($nom=="Dignatario")return false;
        if($ciu)
        {
            $this->setdatoCiu($ciu);
            return $this->dato_ciu;
        }
        else if($oem)
        {
            $this->setdatoOem($oem);
            return $this->dato_oem;
        }
        else if($esp)
        {
            $this->setdatoEnt($esp);
            return $this->dato_ent;
        }
        else if($fun)
        {
            $this->setdatoFun($fun);
            return $this->dato_fun;
        }
        return false;
        
    }

     public function obtieneDatosDir($codi_dir_drecciones)
    {
        $isql = "select a.SGD_CIU_CODIGO
                ,a.SGD_OEM_CODIGO
                ,a.SGD_ESP_CODI
                ,a.SGD_DOC_FUN
                ,a.SGD_DIR_NOMBRE
                ,a.SGD_DIR_DIRECCION AS DIRECCION
                ,b.NOMBRE_CONT AS CONTINENTE
                ,c.NOMBRE_PAIS AS PAIS
                ,d.DPTO_NOMB AS DEPARTAMENTO
                ,e.MUNI_NOMB AS MUNICIPIO
                from sgd_dir_drecciones a
                join SGD_DEF_CONTINENTES b ON a.ID_CONT=b.ID_CONT
                join SGD_DEF_PAISES      c ON a.ID_CONT=c.ID_CONT AND a.ID_PAIS=c.ID_PAIS
                join DEPARTAMENTO        d ON a.ID_CONT=d.ID_CONT AND a.ID_PAIS=d.ID_PAIS AND a.DPTO_CODI=d.DPTO_CODI
                join MUNICIPIO          e ON a.ID_CONT=e.ID_CONT AND a.ID_PAIS=e.ID_PAIS AND a.DPTO_CODI=e.DPTO_CODI AND a.MUNI_CODI=e.MUNI_CODI
                where  sgd_dir_codigo = $codi_dir_drecciones";
        return $this->dato_dir_direccion =  $this->conn->GetArray($isql);
    }

    public function setdatoCiu($codi_ciu)
    {
        $sql="select a.SGD_CIU_CEDULA as ID
                    ,a.SGD_CIU_NOMBRE as NOMBRE,".
                    $this->conn->Concat("a.SGD_CIU_APELL1","' '","a.SGD_CIU_APELL2")." as APELLIDO
                    ,a.SGD_CIU_CODIGO
                    ,a.SGD_CIU_DIRECCION as DIRECCION
                    ,a.SGD_CIU_TELEFONO AS TELEFONO
                    ,b.NOMBRE_CONT AS CONTINENTE
                    ,c.NOMBRE_PAIS AS PAIS
                    ,d.DPTO_NOMB AS DEPARTAMENTO
                    ,e.MUNI_NOMB AS MUNICIPIO
              from SGD_CIU_CIUDADANO a
              join SGD_DEF_CONTINENTES b ON a.ID_CONT=b.ID_CONT
              join SGD_DEF_PAISES      c ON a.ID_CONT=c.ID_CONT AND a.ID_PAIS=c.ID_PAIS
              join DEPARTAMENTO        d ON a.ID_CONT=d.ID_CONT AND a.ID_PAIS=d.ID_PAIS AND a.DPTO_CODI=d.DPTO_CODI
              join MUNICIPIO          e ON a.ID_CONT=e.ID_CONT AND a.ID_PAIS=e.ID_PAIS AND a.DPTO_CODI=e.DPTO_CODI AND a.MUNI_CODI=e.MUNI_CODI
              WHERE  SGD_CIU_CODIGO = $codi_ciu";
        $this->dato_ciu= $this->conn->GetArray($sql);
        
    }
    public function setdatoEnt($codi_ent)
    {
        $sql="select a.NIT_DE_LA_EMPRESA as ID
                    ,a.NOMBRE_DE_LA_EMPRESA as NOMBRE
                    ,a.NOMBRE_REP_LEGAL as APELLIDO
                    ,a.IDENTIFICADOR_EMPRESA
                    ,a.DIRECCION as DIRECCION
                    ,a.TELEFONO_1 AS TELEFONO
                    ,b.NOMBRE_CONT AS CONTINENTE
                    ,c.NOMBRE_PAIS AS PAIS
                    ,d.DPTO_NOMB AS DEPARTAMENTO
                    ,e.MUNI_NOMB AS MUNICIPIO
              from BODEGA_EMPRESAS a
              join SGD_DEF_CONTINENTES b ON a.ID_CONT=b.ID_CONT
              join SGD_DEF_PAISES      c ON a.ID_CONT=c.ID_CONT AND a.ID_PAIS=c.ID_PAIS
              join DEPARTAMENTO        d ON a.ID_CONT=d.ID_CONT AND a.ID_PAIS=d.ID_PAIS AND a.CODIGO_DEL_DEPARTAMENTO=d.DPTO_CODI
              join MUNICIPIO          e ON a.ID_CONT=e.ID_CONT AND a.ID_PAIS=e.ID_PAIS AND a.CODIGO_DEL_DEPARTAMENTO=e.DPTO_CODI AND a.CODIGO_DEL_MUNICIPIO=e.MUNI_CODI
              WHERE  IDENTIFICADOR_EMPRESA = $codi_ent";
        $this->dato_ent= $this->conn->GetArray($sql);
    }
    public function setdatoFun($codi_fun)
    {
        $sql="select USUA_DOC as ID
                    ,USUA_NOMB as NOMBRE
                    ,'-' as APELLIDO
                    ,USUA_DOC
                    ,d.depe_nomb as DIRECCION
                    ,USUA_EXT AS TELEFONO
              from USUARIO u
              join DEPENDENCIA d on u.depe_codi=d.depe_codi
	      WHERE  USUA_DOC = $codi_fun";
        $this->dato_fun= $this->conn->GetArray($sql);
    }
    public function setdatoOem($codi_oem)
    {
        $sql="select a.SGD_OEM_NIT as ID
                    ,a.SGD_OEM_OEMPRESA as NOMBRE
                    ,a.SGD_OEM_REP_LEGAL as APELLIDO
                    ,a.SGD_OEM_CODIGO
                    ,a.SGD_OEM_DIRECCION as DIRECCION
                    ,a.SGD_OEM_TELEFONO AS TELEFONO
                    ,b.NOMBRE_CONT AS CONTINENTE
                    ,c.NOMBRE_PAIS AS PAIS
                    ,d.DPTO_NOMB AS DEPARTAMENTO
                    ,e.MUNI_NOMB AS MUNICIPIO
              from SGD_OEM_OEMPRESAS a
              join SGD_DEF_CONTINENTES b ON a.ID_CONT=b.ID_CONT
              join SGD_DEF_PAISES      c ON a.ID_CONT=c.ID_CONT AND a.ID_PAIS=c.ID_PAIS
              join DEPARTAMENTO        d ON a.ID_CONT=d.ID_CONT AND a.ID_PAIS=d.ID_PAIS AND a.DPTO_CODI=d.DPTO_CODI
              join MUNICIPIO          e ON a.ID_CONT=e.ID_CONT AND a.ID_PAIS=e.ID_PAIS AND a.DPTO_CODI=e.DPTO_CODI AND a.MUNI_CODI=e.MUNI_CODI
	      WHERE  SGD_OEM_CODIGO = $codi_oem";
          $this->dato_oem= $this->conn->GetArray($sql);
    }

    public function getdatoCiu()
    {
        return $this->dato_ciu;
    }
    public function getdatoEnt()
    {
        return $this->dato_ent;
    }
    public function getdatoFun()
    {
        return $this->dato_fun;
    }
    public function getdatoOem()
    {
        return $this->dato_oem;
    }
}

?>
