<?php
class ResponsableV
{

    private $numEmpleado;
    private $numLicencia;
    private $nombre;
    private $apellido;
    private $mensajeError;

    public function __construct()
    {
        $this->numEmpleado = '';
        $this->numLicencia = '';
        $this->nombre = '';
        $this->apellido = '';
    }
    //--------------------------------------Get

    public function getNumEmpleado()
    {
        return $this->numEmpleado;
    }
    public function getNumLicencia()
    {
        return $this->numLicencia;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getApellido()
    {
        return $this->apellido;
    }

    public function getError()
    {
        return $this->mensajeError;
    }

    //--------------------------------------Set

    public function setNumEmpleado($numEmpleado)
    {
        $this->numEmpleado = $numEmpleado;

        return $this;
    }

    public function setNumLicencia($numLicencia)
    {
        $this->numLicencia = $numLicencia;

        return $this;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function setError($mensajeError)
    {
        $this->mensajeError = $mensajeError;
    }

    //--------------------------------------toString

    public function __toString()
    {
        $mostrar =
            "Datos del responsable" . "\n" .
            "Nombre: " . $this->getNombre() . "\n" .
            "Apellido: " . $this->getApellido() . "\n" .
            "Numero de licencia: " . $this->getNumLicencia() . "\n" .
            "Numero de empleado: " . $this->getNumEmpleado() . "\n";
        return $mostrar;
    }

    public function cargar($nombre, $apellido, $numLicencia, $numEmpleado)
    {
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setNumLicencia($numLicencia);
        $this->setNumEmpleado($numEmpleado);
    }

    //---------------------------- Insertar nuevo Responsable
    public function insertarResponsable()
    {
        $objBase = new BaseDatos();
        $respuesta = false;

        $nombre = $this->getNombre();
        $apellido = $this->getApellido();
        $numeroLicencia = $this->getNumLicencia();
        $numEmpleado = $this->getNumEmpleado();

        $consultaInsertResponsable = "INSERT INTO responsable 
            (rapellido, rnombre, rnumeroempleado, rnumerolicencia)
            VALUES ('$apellido','$nombre','$numEmpleado','$numeroLicencia')";

        if ($objBase->Iniciar()) {
            if ($objBase->Ejecutar($consultaInsertResponsable)) {
                $respuesta = true;
            } else {
                $this->setError($objBase->getError());
            }
        } else {
            $this->setError($objBase->getError());
        }
        return $respuesta;
    }
    //---------------------------- Eliminar Responsable
    public function eliminarResponsable()
    {
        $objBase = new BaseDatos();
        $respuesta = false;

        $id = $this->getNumEmpleado();

        $deleteResponsable = "DELETE FROM responsable WHERE rnumeroempleado='$id'";
        if ($objBase->Iniciar()) {
            if ($objBase->Ejecutar($deleteResponsable)) {
                $respuesta = true;
            } else {
                $this->setError($objBase->getError());
            }
        } else {
            $this->setError($objBase->getError());
        }
        return $respuesta;
    }

    public function modificar($numEmpleadoModificar)
    {

        $objBase = new BaseDatos();
        $respuesta = false;

        $numEmpleado = $this->getNumEmpleado();
        $nombre = $this->getNombre();
        $numeroLicencia = $this->getNumLicencia();
        $apellido = $this->getApellido();

        $updateResponsable = "UPDATE responsable
            SET rnombre = '$nombre' , rapellido = '$apellido' , rnumeroempleado = '$numEmpleado' , rnumerolicencia = '$numeroLicencia'
            WHERE rnumeroempleado = $numEmpleadoModificar ";

        if ($objBase->Iniciar()) {
            if ($objBase->Ejecutar($updateResponsable)) {
                $respuesta = true;
            } else {
                $this->setError($objBase->getError());
            }
        } else {
            $this->setError($objBase->getError());
        }
        return $respuesta;
    }
    /**
     * Busca un responsable por su id y lo carga en el objeto
     */
    public function buscar($numEmpleado)
    {
        $objBase = new BaseDatos();
        $consultaResponsable = "SELECT * FROM responsable WHERE rnumeroempleado = $numEmpleado";
        $respuesta = false;
        if ($objBase->Iniciar()) {
            if ($objBase->Ejecutar($consultaResponsable)) {
                if ($row = $objBase->Registro()) {
                    $this->cargar($row['rnombre'], $row['rapellido'], $row['rnumerolicencia'], $row['rnumeroempleado']);
                    $respuesta = true;
                }
            } else {
                $this->setError($objBase->getError());
            }
        } else {
            $this->setError($objBase->getError());
        }
        return $respuesta;
    }
    /**
     * Lista todos los responsables de la base de datos
     */
    public function listar($condicion = '')
    {

        $objBase = new BaseDatos();
        $consultaResponsable = 'SELECT * FROM responsable';
        if ($condicion != "") {
            $consultaResponsable = $consultaResponsable . ' WHERE ' . $condicion;
        }
        $arrayResponsables = [];
        if ($objBase->Iniciar()) {
            if ($objBase->Ejecutar($consultaResponsable)) {
                while ($row = $objBase->Registro()) {
                    $numEmpleado = $row['rnumeroempleado'];
                    $nombre = $row['rnombre'];
                    $apellido = $row['rapellido'];
                    $numLicencia = $row['rnumerolicencia'];
                    $objResponsable = new ResponsableV();
                    $objResponsable->cargar($nombre, $apellido, $numLicencia, $numEmpleado);
                    array_push($arrayResponsables, $objResponsable);
                }
            } else {
                $this->setError($objBase->getError());
            }
        } else {
            $this->setError($objBase->getError());
        }
        return $arrayResponsables;
    }
}
