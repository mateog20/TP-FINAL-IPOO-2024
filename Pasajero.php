<?php

/**
 * Clase para almacenar informacion de un pasajero
 * Cada pasajero guarda  su “nombre”, “apellido” y “numero de documento”.
 */
class Pasajero
{
    private $numDni;
    private $nombre;
    private $apellido;
    private $numTelefono;
    private $objViaje;  // ObjViaje -> id
    private $mensajeError;

    public function __construct()
    {
        $this->numDni = '';
        $this->nombre = '';
        $this->apellido = '';
        $this->numTelefono = '';
        $this->objViaje = '';
    }

    public function getNumDni()
    {
        return $this->numDni;
    }

    public function setNumDni($numDni)
    {
        $this->numDni = $numDni;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    public function getNumTelefono()
    {
        return $this->numTelefono;
    }

    public function setNumTelefono($numTelefono)
    {
        $this->numTelefono = $numTelefono;
    }

    public function getTicket()
    {
        return $this->objViaje;
    }

    public function setTicket($objViaje)
    {
        $this->objViaje = $objViaje;
    }
    public function getMensajeError()
    {
        return $this->mensajeError;
    }

    public function setMensajeError($mensaje)
    {
        $this->mensajeError = $mensaje;
    }
    public function __toString()
    {
        $mostrar =
            "Datos del pasajero" . "\n" .
            "Nombre: " . $this->getNombre() . "\n" .
            "Apellido: " . $this->getApellido() . "\n" .
            "DNI: " . $this->getNumDni() . "\n" .
            "Telefono: " . $this->getNumTelefono() . "\n" .
            "Ticket: " . $this->getTicket() . "\n";


        return $mostrar;
    }
    public function cargar($nombre, $apellido, $dni, $telefono, $objViaje)
    {
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setNumDni($dni);
        $this->setNumTelefono($telefono);
        $this->setTicket($objViaje);
    }
    public function insertarPasajero()
    {
        $objBase = new BaseDatos();
        $respuesta = false;

        $nombre = $this->getNombre();
        $apellido = $this->getApellido();
        $tiket = $this->getTicket();
        $telefono = $this->getNumTelefono();
        $dni = $this->getNumDni();

        $insertPasajero = "INSERT INTO pasajero (idviaje, papellido, pdocumento, pnombre, ptelefono)
            VALUES ('$tiket','$apellido','$dni','$nombre','$telefono')";

        if ($objBase->Iniciar()) {
            if ($objBase->Ejecutar($insertPasajero)) {
                $respuesta = true;
            } else {
                $this->setMensajeError($objBase->getError());
            }
        } else {
            $this->setMensajeError($objBase->getError());
        }
        return $respuesta;
    }

    public function eliminar()
    {
        $objBase = new BaseDatos();
        $respuesta = false;

        $dni = $this->getNumDni();

        $deletePasajero = "DELETE FROM pasajero WHERE pdocumento='$dni'";

        if ($objBase->Iniciar()) {
            if ($objBase->Ejecutar($deletePasajero)) {
                $respuesta = true;
            } else {
                $this->setMensajeError($objBase->getError());
            }
        } else {
            $this->setMensajeError($objBase->getError());
        }
        return $respuesta;
    }


    public function buscar($dniIngresado)
    {
        $objBase = new BaseDatos();
        $consultaPasajero = "SELECT * FROM pasajero WHERE pdocumento = $dniIngresado";
        $respuesta = false;
        if ($objBase->Iniciar()) {
            if ($objBase->Ejecutar($consultaPasajero)) {
                if ($row = $objBase->Registro()) {
                    $objViaje = new Viaje();
                    $objViaje->buscar( $row['idviaje']);
                    $this->cargar($row['pnombre'], $row['papellido'], $row['pdocumento'], $row['ptelefono'], $objViaje->getCodigo());
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
        $arrayPasajeros = [];
        $objBase = new BaseDatos();
        $consultaPasajero = 'SELECT * FROM pasajero';
        if ($condicion != "") {
            $consultaPasajero = $consultaPasajero . ' where ' . $condicion;
        }
        $consultaPasajero .= ' order by idviaje';
        if ($objBase->Iniciar()) {
            if ($objBase->Ejecutar($consultaPasajero)) {
                $objViaje = new Viaje();
                while ($row = $objBase->Registro()) {
                    $objViaje->buscar( $row['idviaje']);
                    $id =$objViaje->getCodigo();
                    $nombre = $row['pnombre'];
                    $apellido = $row['papellido'];
                    $dni = $row['pdocumento'];
                    $telefono = $row['ptelefono'];
                    $objPasajero = new Pasajero();
                    $objPasajero->cargar($nombre, $apellido, $dni, $telefono, $id);
                    array_push($arrayPasajeros, $objPasajero);
                }
            } else {
                $this->setMensajeError($objBase->getError());
            }
        } else {
            $this->setMensajeError($objBase->getError());
        }
        return $arrayPasajeros;
    }
}
