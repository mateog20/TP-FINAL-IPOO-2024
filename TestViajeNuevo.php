<?php
include_once "Viaje.php";
include_once "ResponsableV.php";
include_once "Pasajero.php";
include_once "Empresa.php";

$objViaje = new Viaje();
$objEmpresa = new Empresa();
$objPasajero = new Pasajero();

// FUNCIONES

/**
 * Muestra las opciones del menu principal
 */
function menuPrincipal()
{
    $mostrar =
        "💠💠 MENU PRINCIPAL 💠💠" . "\n" .
        "1. CREAR EMPRESA / MODIFICAR EMPRESA" . "\n" .
        "2. CREAR VUELO / MODIFICAR VUELO" . "\n" .
        "3. CREAR RESPONSABLE DE VUELO" . "\n" .
        "4. CREAR UN NUEVO PASAJERO" . "\n" .
        "5. MOSTRAR Ids DISPONIBLES" . "\n" .
        "0. SALIR" . "\n" .
        "INGRESE UNA OPCION: ";
    return $mostrar;
}
/**
 * Comprueba si un id existe en la base de datos
 * @param int $id   
 * @param string $clase
 * @return boolean true si el id existe, false si no
 */
function confirmarId($id, $clase)
{
    $existe = false; // Inicializamos la variable $existe como falso por defecto

    switch ($clase) {
        case 'viaje':
            $objViaje = new Viaje();
            $existe = $objViaje->buscar($id);
            break;
        case 'empresa':
            $objEmpresa = new Empresa();
            $existe = $objEmpresa->buscar($id);
            break;
        case 'responsable':
            $objResponsable = new ResponsableV();
            $existe = $objResponsable->buscar($id);
            break;
        case 'pasajero':
            $objPasajero = new Pasajero();
            $existe = $objPasajero->buscar($id);
            break;
        default:
            $existe = false;
            break;
    }

    return $existe;
}
/**
 * Muestra las ids disponibles
 */
function mostrarIdsDisponibles($tipo = 'todos')
{
    $objEmpresa = new Empresa();
    $objResponsable = new ResponsableV();
    $objViaje = new Viaje();

    $mostrar = '';

    if ($tipo === 'empresa' || $tipo === 'todos') {
        $idEmpresas = $objEmpresa->listar();
        $mostrar .= "IDs Empresa: ";
        foreach ($idEmpresas as $empresa) {
            $mostrar .= encerrarCuadrado($empresa->getIdEmpresa());
        }
        $mostrar .= "\n";
    }

    if ($tipo === 'responsable' || $tipo === 'todos') {
        $idResponsables = $objResponsable->listar();
        $mostrar .= "IDs Responsable: ";
        foreach ($idResponsables as $responsable) {
            $mostrar .= encerrarCuadrado($responsable->getNumEmpleado());
        }
        $mostrar .= "\n";
    }
    if ($tipo === 'viaje' || $tipo === 'todos') {
        $idViajes = $objViaje->listar();
        $mostrar .= "IDs Viaje: ";
        foreach ($idViajes as $viaje) {
            $mostrar .= encerrarCuadrado($viaje->getCodigo());
        }
        $mostrar .= "\n";
    }

    return $mostrar;
}

function encerrarCuadrado($id)
{
    // Caracteres ASCII para formar un cuadrado alrededor del ID
    $cuadrado = "[{$id}]";
    return $cuadrado;
}
/**
 * Muestra las opciones del menu de vuelo
 */
function menuVuelo()
{
    $mostrar =
        "🛫 MENU VUELO 🛬 " . "\n" .
        "1. CREAR VUELO" . "\n" .
        "2. MODIFICAR/ELIMINAR VUELO" . "\n" .
        "0. SALIR" . "\n" .
        "INGRESE UNA OPCION: ";
    return $mostrar;
}
/** 
 * Muestra las opciones del menu de modificar vuelo
 */
function menuModificarVuelo()
{
    $mostrar =
        "🔸🔸 MODIFICAR VUELO 🔸🔸" . "\n" .
        "1. Elegir otra empresa (ID) " . "\n" .
        "2. Elegir otro responsable de vuelo (ID)" . "\n" .
        "3. Cantidad maxima de pasajeros" . "\n" .
        "4. Destino del vuelo" . "\n" .
        "5. Costo del vuelo" . "\n" .
        "6. Modificar todos los datos" . "\n" .
        "7. Eliminar el viaje" . "\n" .
        "0. Salir" . "\n" .
        "QUE DATOS DEL VUELO VA A MODIFICAR: ";

    return $mostrar;
}
/**
 * Muestra las opciones del menu de crear vuelo y crea un objeto de la clase viaje
 * @return  objeto de la clase viaje
 */
function menuCrearVuelo()
{
    echo "CREAR VUELO ✔" . "\n";
    // Instanciar objetos necesarios
    $objEmpresa = new Empresa();
    $objResponsable = new ResponsableV();
    $objViaje = new Viaje();

    // Obtener y validar el ID de la empresa
    do {
        echo mostrarIdsDisponibles('empresa');
        echo "Ingrese el ID de la empresa: ";
        $idEmpresa = trim(fgets(STDIN));

        if (empty($idEmpresa) || !confirmarId($idEmpresa, 'empresa')) {
            echo "ID incorrecto o vacío. Ingrese un ID válido.\n";
        } else {
            $objEmpresa->buscar($idEmpresa); // Cargar datos de la empresa seleccionada
        }
    } while (empty($idEmpresa) || !confirmarId($idEmpresa, 'empresa'));

    // Obtener y validar el ID del responsable de vuelo
    do {
        echo mostrarIdsDisponibles('responsable');
        echo "Ingrese el ID del responsable de vuelo: ";
        $idResponsable = trim(fgets(STDIN));

        if (empty($idResponsable) || !confirmarId($idResponsable, 'responsable')) {
            echo "ID incorrecto o vacío. Ingrese un ID válido.\n";
        } else {
            $objResponsable->buscar($idResponsable); // Cargar datos del responsable seleccionado
        }
    } while (empty($idResponsable) || !confirmarId($idResponsable, 'responsable'));

    // Obtener la cantidad máxima de pasajeros
    echo "Ingrese la cantidad máxima de pasajeros: ";
    $pasajeros = trim(fgets(STDIN));

    // Obtener el destino del vuelo
    echo "Ingrese el destino del vuelo: ";
    $destino = trim(fgets(STDIN));

    // Obtener el costo del vuelo
    echo "Ingrese el costo del vuelo: ";
    $costo = trim(fgets(STDIN));

    // Cargar datos en el objeto Viaje
    $objViaje->cargar('', $destino, $pasajeros, $costo, $objEmpresa, $objResponsable);
    return $objViaje;
}
/**
 * Muestra las opciones del menu de crear empresa y crea un objeto de la clase empresa
 * @return  objeto de la clase empresa
 */
function menuCrearEmpresa()
{
    echo 'Ingrese el nombre de la empresa: ';
    $nombre = trim(fgets(STDIN));
    echo 'Ingrese la direccion: ';
    $direccion = trim(fgets(STDIN));
    $objEmpresa = new Empresa();
    $objEmpresa->cargar('', $nombre, $direccion);
    return $objEmpresa;
}
/**
 * Muestra las opciones del menu de modificar empresa
 */
function menuModificarEmpresa()
{
    $mostrar =
        "🔸🔸 MODIFICAR EMPRESA 🔸🔸" . "\n" .
        "1. Nombre de la empresa" . "\n" .
        "2. Direccion de la empresa" . "\n" .
        "3. Eliminar la empresa" . "\n" .
        "0. Salir" . "\n" .
        "QUE DATOS DE LA EMPRESA VA A MODIFICAR: ";
    return $mostrar;
}
/**
 * Muestra las opciones del menu de empresa
 */
function menuEmpresa()
{
    $mostrar =
        " ♦♦♦ MENU EMPRESA ♦♦♦ " . "\n" .
        "1. CREAR EMPRESA" . "\n" .
        "2. MODIFICAR / ELIMINAR EMPRESA" . "\n" .
        "0. SALIR" . "\n" .
        "INGRESE UNA OPCION: ";
    return $mostrar;
}

/**
 * Muestra las opciones del menu de crear pasajero y crea un arreglo de la clase pasajero
 * @return  array de la clase pasajero
 */
function crearPasajeros()
{
    echo "CREAR PASAJERO ✔" . "\n";
    echo "Ingrese su DNI: ";
    $dni = trim(fgets(STDIN));

    echo "Ingrese el nombre del nuevo pasajero: ";
    $nombre = trim(fgets(STDIN));
    echo "Ingrese el apellido del nuevo pasajero: ";
    $apellido = trim(fgets(STDIN));
    echo "Ingrese su numero de telefono: ";
    $telefono = trim(fgets(STDIN));

    $pasajero =
        [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'dni' => $dni,
            'telefono' => $telefono,
        ];
    return $pasajero;
}
function pasajeros($id)
{
    $objPasajero = new Pasajero();
    $arrayPasajeros = $objPasajero->listar('idviaje =' . $id);
    return $arrayPasajeros;
}
function mensajesOperacion($validar)
{
    if ($validar) {
        $mostrar = "✅ OPERACION EXITOSA  ✅" . "\n";
    } else {
        $mostrar = "❌ OPERACION FALLIDA ❌" . "\n";
    }
    return $mostrar;
}
// COMIENZO PROGRAMA PRINCIPAL  

do {
    // Mostrar el menú principal y obtener la selección del usuario
    echo menuPrincipal();
    $seleccionMenu = trim(fgets(STDIN));

    switch ($seleccionMenu) {
        case 1: // MENU EMPRESA
            echo menuEmpresa();
            $seleccionMenuEmpresa = trim(fgets(STDIN));

            switch ($seleccionMenuEmpresa) {
                case 1: // CREAR EMPRESA
                    $objEmpresa = menuCrearEmpresa(); // Esta función debe devolver un objeto Empresa creado           
                    $respuestaEmpresa = $objEmpresa->insertarEmpresa();
                    if ($respuestaEmpresa) {
                        echo mensajesOperacion($respuestaEmpresa);
                        echo "Se ha creado la empresa: " . $objEmpresa->getIdEmpresa() . "\n";
                    } else {
                        echo mensajesOperacion(false) . $objEmpresa->getMensajeError() . "\n";
                    }

                    break;

                case 2: // MODIFICAR EMPRESA

                    $idValido = false;
                    $objEmpresa = new Empresa();
                    do {
                        echo mostrarIdsDisponibles($tipo = 'empresa');
                        echo "Ingrese el id de la empresa a modificar: ";
                        $idEmpresa = trim(fgets(STDIN));
                        if (empty($idEmpresa) || !$objEmpresa->buscar($idEmpresa)) {
                            echo "ID incorrecto. Por favor, intente nuevamente.\n";
                        } else {
                            $idValido = true;
                        }
                    } while (empty($idValido) || !$idValido);
                    // Setea el id de la empresa que se va a modificar
                    echo menuModificarEmpresa();
                    $opcionModificacionEmpresa = trim(fgets(STDIN));

                    // SWITCH PARA MODIFICAR EMPRESA
                    switch ($opcionModificacionEmpresa) {
                        case 1:
                            echo "Ingrese el nuevo nombre de la empresa: ";
                            $nombre = trim(fgets(STDIN));
                            if (!empty($nombre)) {
                                $objEmpresa->setNombre($nombre);
                            }
                            break;

                        case 2:
                            echo "Ingrese la nueva direccion de la empresa: ";
                            $direccion = trim(fgets(STDIN));
                            if (!empty($direccion)) {
                                $objEmpresa->setDireccion($direccion);
                            }
                            break;
                        case 3:
                            if (count($objViaje->listar('idempresa=' . $idEmpresa)) > 0) {
                                echo "No se puede eliminar esta empresa, tiene viajes cargados ";
                            } else {
                                $respDelete = $objEmpresa->eliminarEmpresa();
                                if ($respDelete) {
                                    echo mensajesOperacion($respDelete);
                                } else {
                                    echo mensajesOperacion($respDelete) . $objViaje->getMensajeError() . "\n";
                                }
                            }
                            break;
                        default:
                            echo "Opción no válida.\n";
                            break;
                    }
                    // Realizar la modificación en la base de datos 
                    if ($opcionModificacionEmpresa != 3) {
                        $respuestaEmpresa = $objEmpresa->modificarEmpresa();

                        if ($respuestaEmpresa) {
                            echo mensajesOperacion($respuestaEmpresa);
                        } else {
                            echo mensajesOperacion($respuestaEmpresa) . $objEmpresa->getMensajeError() . "\n";
                        }
                    }
                    break; // FIN MODIFICAR EMPRESA
            }
            break; // FIN MENU EMPRESA

        case 2: // MENU VUELO
            echo menuVuelo();
            $seleccionMenuVuelo = trim(fgets(STDIN));

            switch ($seleccionMenuVuelo) {
                case 1: // CREAR VUELO
                    $condVuelo = true;
                    $objResponsable = new ResponsableV();
                    if (empty($objEmpresa->listar()) || empty($objResponsable->listar())) {
                        echo "No existen empresas/responsables para crear un vuelo" . "\n";
                    } else {
                        $objViaje = menuCrearVuelo(); // Esta función debe devolver un objeto Viaje creado

                        if ($respuestaViaje = $objViaje->insertarViaje()) {
                            echo mensajesOperacion($respuestaViaje);
                            $ticket = $objViaje->getCodigo();
                        } else {
                            echo mensajesOperacion($respuestaViaje) . $objViaje->getMensajeError() . "\n";
                        }

                        echo "Se ha creado el viaje\n";
                    }

                    break;

                case 2: // MODIFICAR VUELO

                    do {
                        echo "▶▶▶▶ VUELOS REGISTRADOS ◀◀◀◀" . "\n";

                        $vuelos = $objViaje->listar();
                        foreach ($vuelos as $vuelo) {
                            echo "Codigo del vuelo: " . $vuelo->getCodigo() . "\n";
                            echo "Destino: " . $vuelo->getDestino() . "\n";
                            echo "-------------------------------------------" . "\n";
                        }
                        echo "\n" . "Ingrese el numero del vuelo que desea modificar: ";
                        $idVuelo = trim(fgets(STDIN));
                        if (empty($idVuelo) || !confirmarId($idVuelo, 'viaje')) {
                            echo '❌ ID inexistente o no válido, seleccione un ID válido ❌' . "\n";
                        }
                    } while (empty($idVuelo) || !confirmarId($idVuelo, 'viaje'));

                    $objViaje->buscar($idVuelo);
                    echo menuModificarVuelo();
                    $opcionModificacionVuelo = trim(fgets(STDIN));

                    switch ($opcionModificacionVuelo) {
                        case 1:
                            echo mostrarIdsDisponibles('empresa');
                            echo "Ingrese el nuevo id de empresa: ";
                            $id = trim(fgets(STDIN));
                            $objEmpresa = new Empresa();

                            if ($objEmpresa->buscar($id)) {
                                $objViaje->setEmpresa($objEmpresa);
                            } else {
                                echo "Error al buscar la empresa: " . $objEmpresa->getMensajeError() . "\n";
                            }

                            break;

                        case 2:
                            echo mostrarIdsDisponibles('responsable');
                            echo "Ingrese el nuevo id de responsable: ";
                            $idResponsable = trim(fgets(STDIN));
                            $objResponsable = new ResponsableV();
                            if ($objResponsable->buscar($idResponsable)) {
                                $objViaje->setResponsableV($objResponsable);
                            } else {
                                echo "Error al buscar el responsable: " . $objResponsable->getError() . "\n";
                            }
                            break;

                        case 3:
                            echo "Ingrese la nueva cantidad maxima de pasajeros: ";
                            $cantMax = trim(fgets(STDIN));
                            $objViaje->setCantMax($cantMax);
                            break;

                        case 4:
                            echo "Ingrese el nuevo destino: ";
                            $destino = trim(fgets(STDIN));
                            $objViaje->setDestino($destino);
                            break;

                        case 5:
                            echo "Ingrese el nuevo costo del viaje: ";
                            $costo = trim(fgets(STDIN));
                            $objViaje->setCostoViaje($costo);
                            break;

                        case 6:
                            echo "Modificar todos los datos del vuelo\n";
                            $objViaje = menuCrearVuelo();
                            $objViaje->cargar($idVuelo, $objViaje->getDestino(), $objViaje->getCantMax(), $objViaje->getCostoViaje(), $objViaje->getEmpresa(), $objViaje->getResponsableV());
                            break;
                        case 7:
                            // Verificar si hay pasajeros asociados al vuelo
                            $objPasajero = new Pasajero();
                            $pasajeros = $objPasajero->listar('idViaje=' . $idVuelo);
                            if (count($pasajeros) > 0) {
                                echo "El vuelo no se puede borrar, hay pasajeros ingresados.\n";
                            } else {
                                $objViaje->eliminarViaje();
                                echo "El viaje ha sido eliminado exitosamente.\n";
                            }
                            break;
                        case 0:
                            echo "Volviendo al menu principal...\n";
                            break;
                        default:
                            echo "Opción no válida.\n";
                            break;
                    }
                    // Realizar la modificación en la base de datos
                    if ($opcionModificacionVuelo != 7) {
                        $respuestaVuelo = $objViaje->modificarViaje();
                        if ($respuestaVuelo) {
                            echo mensajesOperacion($respuestaVuelo);
                        } else {
                            echo mensajesOperacion($respuestaVuelo) . $objViaje->getMensajeError() . "\n";
                        }
                    }
                    break; // FIN MODIFICAR VUELO
            }

            break; // BREAK MENU VUELO

        case 3: // CREAR RESPONSABLE DE VUELO
            echo "Ingrese el numero de licencia: ";
            $numLicencia = trim(fgets(STDIN));
            echo "Ingrese el nombre: ";
            $nombreResponsableV = trim(fgets(STDIN));
            echo "Ingrese el apellido: ";
            $apellidoResponsableV = trim(fgets(STDIN));

            $nuevoResponsable = new ResponsableV();
            $nuevoResponsable->cargar($nombreResponsableV, $apellidoResponsableV, $numLicencia, '');

            $respuestaResponsable = $nuevoResponsable->insertarResponsable();

            if ($respuestaResponsable) {
                echo mensajesOperacion($respuestaResponsable);
                echo "El responsable de vuelo ha sido creado exitosamente.";
            } else {
                echo mensajesOperacion($respuestaResponsable) . $nuevoResponsable->getError() . "\n";
            }

            break;

        case 4: // CREAR UN NUEVO PASAJERO
            $objViaje = new Viaje();
            if (empty($objViaje->listar())) {
                echo "No hay viajes disponibles, para asignar pasajeros" . "\n";
            } else {
                $pasajero = crearPasajeros();
                do {
                    echo mostrarIdsDisponibles('viaje');
                    echo "¿Que viaje abordará el nuevo pasajero?: ";
                    $ticket = trim(fgets(STDIN));

                    // Verificar si el ID de viaje existe
                    if (empty($ticket) || !confirmarId($ticket, 'viaje')) {
                        echo '❌ ID inexistente, seleccione un ID válido ❌' . "\n";
                    }
                } while (empty($ticket) || !confirmarId($ticket, 'viaje'));

                // Crear un objeto Viaje y buscar el viaje por su ID
                $objViaje->setColPasajero(pasajeros($ticket));
                $objViaje->buscar($ticket);

                // Verificar si hay espacio disponible para el nuevo pasajero
                if ($objViaje->hayPasajesDisponibles()) {
                    $nuevoPasajero = new Pasajero();
                    $nuevoPasajero->cargar($pasajero['nombre'], $pasajero['apellido'], $pasajero['dni'], $pasajero['telefono'], $ticket);

                    // Insertar el pasajero
                    $respuestaPasajero = $nuevoPasajero->insertarPasajero();
                    if ($respuestaPasajero) {
                        echo mensajesOperacion($respuestaPasajero);
                    } else {
                        echo mensajesOperacion($respuestaPasajero) . $nuevoPasajero->getMensajeError() . "\n";
                    }
                } else {
                    echo 'No hay espacio disponible para este viaje.' . "\n";
                }
            }

            break; // FIN CREAR UN NUEVO PASAJERO

        case 5:
            // LISTA DE IDS
            echo "------------------------------\n";
            echo "🎫LISTA DE LOS ID DISPONIBLES🎫\n";
            echo mostrarIdsDisponibles();
            echo "------------------------------\n";
            break;

        case 0: // SALIR
            echo "Saliendo del programa...";
            break;

        default:
            echo "Opción no válida.\n";
            break;
    }
} while ($seleccionMenu != 0);
