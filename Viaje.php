<?php
class Viaje
{
    private $codigo;
    private $destino;
    private $canMax;
    private $costoViaje;
    private $objEmpresa;
    private $objResponsable;
    private $colPasajero;
    private $mensajeError;

    public function __construct()
    {
        $this->codigo = '';
        $this->destino = '';
        $this->canMax = '';
        $this->costoViaje = '';
        $this->objEmpresa = '';
        $this->objResponsable = '';
        $this->colPasajero = array();
    }
    //------------------------------ Get
    public function getCodigo()
    {
        return $this->codigo;
    }
    public function getDestino()
    {
        return $this->destino;
    }
    public function getCantMax()
    {
        return $this->canMax;
    }

    public function getCostoViaje()
    {
        return $this->costoViaje;
    }
    public function getEmpresa()
    {
        return $this->objEmpresa;
    }

    public function getResponsableV()
    {
        return $this->objResponsable;
    }
    public function getColPasajero()
    {
        return $this->colPasajero;
    }

    public function getMensajeError()
    {
        return $this->mensajeError;
    }

    //------------------------------ Set
    public function setMensajeError($mensajeError)
    {
        $this->mensajeError = $mensajeError;

        return $this;
    }
    public function setCodigo($cod)
    {
        $this->codigo = $cod;
    }
    public function setDestino($destino)
    {
        $this->destino = $destino;
    }
    public function setCantMax($cant)
    {
        $this->canMax = $cant;
    }
    public function setCostoViaje($costoViaje)
    {
        $this->costoViaje = $costoViaje;
    }

    public function setEmpresa(Empresa $empresa)
    {
        $this->objEmpresa = $empresa;
    }
    public function setResponsableV(ResponsableV $responsable)
    {
        $this->objResponsable = $responsable;
    }
    public function setColPasajero($colPasajero)
    {
        $this->colPasajero = $colPasajero;
    }
    public function cargar($idViaje, $destino, $cantMax, $costo, $objEmpresa, $objResp)
    {
        $this->codigo = $idViaje;
        $this->destino = $destino;
        $this->canMax = $cantMax;
        $this->costoViaje = $costo;
        $this->objEmpresa = $objEmpresa;
        $this->objResponsable = $objResp;
    }
    //-------------------------------toString
    public function recorrerPasajeros()
    {
        $salida = "";
        foreach ($this->getColPasajero() as $objPasajero) {
            $salida .= $objPasajero . "\n";
        }
        return $salida;
    }
    public function __toString()
    {
        $empresa = $this->getEmpresa();
        $responsable = $this->getResponsableV();
        if ($responsable instanceof ResponsableV) {
            $numEmpleado = $responsable->getNumLicencia();
        } else {
            $numEmpleado = "N/A";
        }
        if ($empresa instanceof Empresa) {
            $idEmpresa = $empresa->getIdEmpresa();
        } else {
            $idEmpresa = "N/A";
        }
        $salida =
            "\n" .
            "Codigo de viaje: " . $this->getCodigo()
            . "\n" .
            "Destino: " . $this->getDestino()
            . "\n" .
            "Cantidad de pasajeros maxima: " . $this->getCantMax()
            . "\n" .
            "Costo del viaje: $" . $this->getCostoViaje() . "\n" .
            "Pasajeros: " . $this->recorrerPasajeros() . "\n" .
            "Responsable: " . $numEmpleado . "\n" .
            "Id de la empresa: " . $idEmpresa . "\n";
        return $salida;
    }

    public function hayPasajesDisponibles()
    {
        $asientosDisponibles = $this->getCantMax() - count($this->getColPasajero());
        return $asientosDisponibles > 0;
    }



    //---------------------------- Insertar nuevo pasajero
    public function insertarViaje()
    {

        $objBase = new BaseDatos();
        $respuesta = false;


        $destino = $this->getDestino();
        $canMax = $this->getCantMax();
        $idEmpresa = $this->getEmpresa()->getIdEmpresa();
        $numResponsable = $this->getResponsableV()->getNumLicencia();
        $importe = $this->getCostoViaje();

        $insertViaje = "INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte )
                VALUES ('$destino','$canMax','$idEmpresa','$numResponsable','$importe')";

        if ($objBase->iniciar()) {
            if ($objBase->Ejecutar($insertViaje)) {
                $respuesta = true;
                //$this->setCodigo($objBase->devuelveIDInsercion($insertViaje));
            } else {
                $this->setMensajeError($objBase->getError());
            }
        } else {
            $this->setMensajeError($objBase->getError());
        }
        // $this->setCodigo($objBase->devuelveIDInsercion($insertViaje));
        return $respuesta;
    }

    public function modificarViaje()
    {
        $objBase = new BaseDatos();
        $conexionBd = $objBase->iniciar();
        $respuesta = false;

        $idViaje = $this->getCodigo();
        $destino = $this->getDestino();
        $canMax = $this->getCantMax();
        $objEmpresa = $this->getEmpresa(); // Obtener el objeto Empresa
        $objResponsable = $this->getResponsableV(); // Obtener el objeto ResponsableV
        $importe = $this->getCostoViaje();

        if ($objEmpresa instanceof Empresa && $objResponsable instanceof ResponsableV) {
            $idEmpresa = $objEmpresa->getIdEmpresa();
            $numResponsable = $objResponsable->getNumEmpleado();
        } else {
            $this->setMensajeError("Error: Empresa o Responsable no son instancias válidas.");
            return false;
        }


        $consultaModificarVuelo = "UPDATE viaje SET vdestino = '$destino', vcantmaxpasajeros = '$canMax', vimporte = '$importe', rnumeroempleado = '$numResponsable', idempresa = '$idEmpresa'
        WHERE idviaje = $idViaje";

        if ($conexionBd) {
            if ($objBase->Ejecutar($consultaModificarVuelo)) {
                $respuesta = true;
            } else {
                $this->setMensajeError($objBase->getError());
            }
        } else {
            $this->setMensajeError($objBase->getError());
        }

        return $respuesta;
    }

    public function eliminarViaje()
    {

        $objBase = new BaseDatos();
        $respuesta = false;
        $id = $this->getCodigo();

        $deleteViaje = "DELETE FROM viaje WHERE idviaje='$id'";

        if ($objBase->iniciar()) {
            if ($objBase->Ejecutar($deleteViaje)) {
                $respuesta = true;
            } else {
                $this->setMensajeError($objBase->getError());
            }
        } else {
            $this->setMensajeError($objBase->getError());
        }
        return $respuesta;
    }
    public function buscar($idRecibida)
    {
        $objBase = new BaseDatos();
        $consultaViaje = "SELECT * FROM viaje WHERE idviaje = $idRecibida";
        $respuesta = false;
        if ($objBase->Iniciar()) {
            if ($objBase->Ejecutar($consultaViaje)) {
                $objResponsable = new ResponsableV();
                $objEmpresa = new Empresa();

                if ($row = $objBase->Registro()) {
                    if ($objResponsable->buscar($row['rnumeroempleado']) && $objEmpresa->buscar($row['idempresa'])) {
                        $this->cargar($row['idviaje'], $row['vdestino'], $row['vcantmaxpasajeros'], $row['vimporte'], $objEmpresa, $objResponsable);
                        $respuesta = true;
                    } else {
                        echo "Error: No se pudo encontrar la empresa o el responsable.\n";
                    }
                } else {
                    echo "Error: No se encontraron registros en la consulta.\n";
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
        $arrayViajes = [];
        $objBase = new BaseDatos();
        $consultaViaje = 'SELECT * FROM viaje';
        if ($condicion != "") {
            $consultaViaje = $consultaViaje . ' WHERE ' . $condicion;
        }
        //    $consultaViaje.=' order by idempresa';
        if ($objBase->Iniciar()) {
            if ($objBase->Ejecutar($consultaViaje)) {
                $objResponsable = new ResponsableV();
                $objEmpresa = new Empresa();

                while ($row = $objBase->Registro()) {

                    $objResponsable->buscar($row['rnumeroempleado']);
                    $objEmpresa->buscar($row['idempresa']);

                    $idViaje = $row['idviaje'];
                    $destino = $row['vdestino'];
                    $cantMax = $row['vcantmaxpasajeros'];
                    $idEmpresa = $objEmpresa->getIdEmpresa();
                    $idResponsable = $objResponsable->getNumEmpleado();
                    $costo = $row['vimporte'];

                    $objViaje = new Viaje();
                    $objViaje->cargar($idViaje, $destino, $cantMax, $costo, $idEmpresa, $idResponsable);
                    array_push($arrayViajes, $objViaje);
                }
            } else {
                $this->setMensajeError($objBase->getError());
            }
        } else {
            $this->setMensajeError($objBase->getError());
        }
        return $arrayViajes;
    }
}
