<?php
include_once "BaseDatos.php";
class Empresa
{
    private $eNombre;
    private $eDireccion;
    private $idEmpresa;
    private $mensajeError;

    public function __construct()
    {
        $this->eNombre = '';
        $this->eDireccion = '';
        $this->idEmpresa = '';
    }

    public function getNombre()
    {
        return $this->eNombre;
    }
    public function getDireccion()
    {
        return $this->eDireccion;
    }
    public function getIdEmpresa()
    {
        return $this->idEmpresa;
    }
    public function getMensajeError()
    {
        return $this->mensajeError;
    }
    public function setNombre($nombre)
    {
        $this->eNombre = $nombre;
    }
    public function setDireccion($direccion)
    {
        $this->eDireccion = $direccion;
    }
    public function setIdEmpresa($id)
    {
        $this->idEmpresa = $id;
    }
    public function setMensajeError($mensaje)
    {
        $this->mensajeError = $mensaje;
    }

    public function cargar($id, $Nom, $direccion)
    {
        $this->setIdEmpresa($id);
        $this->setNombre($Nom);
        $this->setDireccion($direccion);
    }
    public function __toString()
    {
        $mostrar =
            "Id Empresa: " . $this->getIdEmpresa() . "\n" .
            "Nombre de la empresa: " . $this->getNombre() . "\n" .
            "Direccion de la empresa: " . $this->getDireccion() . "\n";
        return $mostrar;
    }
    /**
     * Inserta la empresa en la base de datos
     */
    public function insertarEmpresa()
    {
        $objBase = new BaseDatos();
        $respuesta = false;

        $id = $this->getIdEmpresa();
        $nombre = $this->getNombre();
        $direccion = $this->getDireccion();

        $insertEmpresa = "INSERT INTO empresa(idempresa, enombre, edireccion)
            VALUES ('$id','$nombre','$direccion')";

        if ($objBase->Iniciar()) {
            if ($objBase->Ejecutar($insertEmpresa)) {
                $respuesta =  true;
            } else {
                $this->setMensajeError($objBase->getError());
            }
        } else {
            $this->setMensajeError($objBase->getError());
        }

        return $respuesta;
    }

    /**
     * Modifica los datos de la empresa en la base de datos
     */
    public function modificarEmpresa()
    {
        // Establece la conexión con la base de datos
        $objBase = new BaseDatos();
        $conexionBd = $objBase->iniciar();
        $respuesta = false;
        // Recupera los datos que se van a modificar
        $id = $this->getIdEmpresa();
        $nombre = $this->getNombre();
        $direccion = $this->getDireccion();

        // Verifica que los datos no esten vacios
        if (empty($id) || empty($nombre) || empty($direccion)) {
            $this->setMensajeError("Datos incompletos para modificar la empresa.");
            return false;
        }
        // UPDATE envia los datos a la base de datos para ser modificados
        $insertar = "UPDATE empresa SET enombre = '$nombre' , edireccion = '$direccion' WHERE idempresa = $id ";


        if ($conexionBd) {
            if ($objBase->Ejecutar($insertar)) {

                $respuesta = true;
            } else {
                $this->setMensajeError($objBase->getError());
            }
        } else {
            $this->setMensajeError($objBase->getError());
        }
        return $respuesta;
    }

    /**
     * Elimina la empresa de la base de datos
     */
    public function eliminarEmpresa()
    {
        $base = new BaseDatos();
        $respuesta = false;

        $id = $this->getIdEmpresa();

        $deleteEmpresa = "DELETE FROM empresa WHERE idempresa='$id'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($deleteEmpresa)) {
                $respuesta = true;
            } else {
                $this->setMensajeError($base->getError());
            }
        } else {
            $this->setMensajeError($base->getError());
        }
        return $respuesta;
    }

    public function buscar($idEmpresa)
    {
        $objBase = new BaseDatos();
        $consultaEmpresa = "SELECT * FROM empresa WHERE idempresa = $idEmpresa";
        $respuesta = false;
        if ($objBase->Iniciar()) {
            if ($objBase->Ejecutar($consultaEmpresa)) {
                if ($row = $objBase->Registro()) {
                    $this->cargar($row['idempresa'], $row['enombre'], $row['edireccion']);
                    $respuesta = true;
                }
            } else {
                $this->setMensajeError($objBase->getError());
            }
        } else {
            $this->setMensajeError($objBase->getError());
        }
        return $respuesta;
    }
    public function listar($condicion = "")
    {
        $arrayEmpresas = [];
        $objBase = new BaseDatos();
        $consultaEmpresa = 'SELECT * FROM empresa';
        if ($condicion != "") {
            $consultaEmpresa = $consultaEmpresa . ' where ' . $condicion;
        }
        $consultaEmpresa .= ' order by idempresa';
        if ($objBase->Iniciar()) {
            if ($objBase->Ejecutar($consultaEmpresa)) {
                while ($row = $objBase->Registro()) {
                    $id = $row['idempresa'];
                    $nombre = $row['enombre'];
                    $direccion = $row['edireccion'];
                    $objEmpresa = new Empresa();
                    $objEmpresa->cargar($id, $nombre, $direccion);
                    array_push($arrayEmpresas, $objEmpresa);
                }
            } else {
                $this->setMensajeError($objBase->getError());
            }
        } else {
            $this->setMensajeError($objBase->getError());
        }
        return $arrayEmpresas;
    }
}
