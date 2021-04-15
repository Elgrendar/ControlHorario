<?php
session_start();
require_once("../../inc/configuracion.php");
require_once("../../inc/funciones.php");
require_once("../../inc/fpdf/fpdf.php");
if (comprobarsesion()) {
    header("location:../../index.php");
}
if (isset($_SESSION['iniciada']) && $_SESSION['iniciada'] === true) {
    if (!empty($_GET)) {
        if (isset($_GET['fecha'])) {
            $fecha = date("Y/m/d", strtotime(filter_input(INPUT_GET, 'fecha')));
            if($fecha=="1970/01/01"){
                $fecha = date("Y/m/d", getdate()[0]);
            }
        } else {
            $fecha = date("Y/m/d", getdate()[0]);
        }
    } else {
        $fecha = date("Y/m/d", getdate()[0]);
    }
    //ancho de las columnas
    $w = array(10, 60, 30, 30, 30, 30);
    class PDF extends FPDF
    {
        // Cabecera de página
        function Header()
        {
            if (!empty($_GET)) {
                if (isset($_GET['fecha'])) {
                    $fecha = date("d/m/Y", strtotime(filter_input(INPUT_GET, 'fecha')));
                    if($fecha=="01/01/1970"){
                        $fecha = date("d/m/Y", getdate()[0]);
                    }
                } else {
                    $fecha = date("d/m/Y", getdate()[0]);
                }
            } else {
                $fecha = date("d/m/Y", getdate()[0]);
            }
            $w = array(10, 60, 30, 30, 30, 30);
            // Logo
            $this->Image('../../assets/img/sistema/logo.png',10,4,15);
            // Arial bold 15
            $this->SetFont('Arial', 'B', 15);
            // Movernos a la derecha
            $this->Cell(50);
            // Título
            $this->Cell(90, 10, 'INFORME DIARIO DEL ' . utf8_decode($fecha), 1, 0, 'C');
            // Salto de línea
            $this->Ln(15);
            $this->Cell($w[0], 10, utf8_decode("ID"), 1, 0, 'C');
            $this->Cell($w[1], 10, utf8_decode("Trabajador"), 1, 0, 'C');
            $this->Cell($w[2], 10, utf8_decode("Entrada"), 1, 0, 'C');
            $this->Cell($w[3], 10, utf8_decode("Salida"), 1, 0, 'C');
            $this->Cell($w[4], 10, utf8_decode("Entrada"), 1, 0, 'C');
            $this->Cell($w[5], 10, utf8_decode("Salida"), 1, 1, 'C');
        }

        // Pie de página
        function Footer()
        {
            // Posición: a 1,5 cm del final
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial', 'I', 8);
            // Número de página
            $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }
    }

    // Creación del objeto de la clase heredada
    $pdf = new PDF();

    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times', '', 12);
    //Color fondo alternar
    $pdf->SetFillColor(224, 235, 255);

    try {
        //establecemos la conexion con la bbdd
        $con = new PDO($conexion, $userBBDD, $passBBDD);
        //Seleccionamos los trabajadores a mostrar
        $stmt = $con->prepare("SELECT idUsuario, nombre, apellido1, apellido2 FROM usuarios WHERE f_baja IS NULL ORDER BY apellido1, apellido2, nombre");

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        try {
            $fill = false;
            foreach ($result as $trabajador) {
                //Hacemos la consulta para conseguir los horarios del trabajador del día seleccionado
                $sql = "SELECT hora, tipo, observaciones FROM registros WHERE idUsuario = " . $trabajador["idUsuario"] . " AND CAST(hora AS DATE)='" .  $fecha . "'";
                $stmt2 = $con->prepare($sql);

                $stmt2->execute();

                $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                //Pintamos las celdas del Id y nombre y apellidos del trabajador
                $pdf->Cell($w[0], 10, utf8_decode($trabajador['idUsuario']), 1, 0, 'C', $fill);
                $pdf->Cell($w[1], 10, utf8_decode($trabajador['apellido1'] . " " . $trabajador['apellido2'] . ", " . $trabajador['nombre']), 1, 0, 'L', $fill);
                //si tiene registros entramos en el trabajador a pintar sus registros
                if ($result2) {
                    $observaciones = false;
                    $i = 0;
                    foreach ($result2 as $entrada) {
                        if ($entrada['tipo'] == "entrada" && $entrada['observaciones'] == "normal") {
                            $i++;
                            $pdf->Cell($w[2], 10, utf8_decode(substr($entrada['hora'], 11, -3)), 1, 0, 'C', $fill);
                        } elseif ($entrada['tipo'] == "salida" && $entrada['observaciones'] == "normal") {
                            $i++;
                            if ($i >= 4) {
                                $pdf->Cell($w[3], 10, utf8_decode(substr($entrada['hora'], 11, -3)), 1, 1, 'C', $fill);
                            } else {
                                $pdf->Cell($w[3], 10, utf8_decode(substr($entrada['hora'], 11, -3)), 1, 0, 'C', $fill);
                            }
                        } elseif ($entrada['observaciones'] != "normal") {
                            $observaciones = true;
                        }
                    }
                    while ($i < 3) {

                        $pdf->Cell($w[$i + 2], 10, "", 1, 0, 'C', $fill);

                        $i++;
                    }
                    if ($i != 4 && $i < 6) {
                        $pdf->Cell($w[$i + 1], 10, "", 1, 1, 'C', $fill);
                    }
                    if ($observaciones) {
                        foreach ($result2 as $entrada) {
                            if ($entrada['tipo'] == "entrada" && $entrada['observaciones'] != "normal") {
                                $pdf->Cell(190, 10, utf8_decode("Ha vuelto a las " . substr($entrada['hora'], 11, -3) . " con motivo: " . $entrada['observaciones']), 1, 1, 'C', $fill);
                            } elseif ($entrada['tipo'] == "salida" && $entrada['observaciones'] != "normal") {
                                $pdf->Cell(190, 10, utf8_decode("El trabajador ha salido a las " . substr($entrada['hora'], 11, -3) . " con motivo: " . $entrada['observaciones']), 1, 1, 'C', $fill);
                            }
                        }
                    }
                } else { //Si no tiene registros pintamos las columnas vacias, aquí podemos comprobar si existe estados
                    //en un futuro como estar de baja o vacaciones o compensación de horas....
                    $pdf->Cell($w[2], 10, "", 1, 0, 'C', $fill);
                    $pdf->Cell($w[3], 10, "", 1, 0, 'C', $fill);
                    $pdf->Cell($w[4], 10, "", 1, 0, 'C', $fill);
                    $pdf->Cell($w[5], 10, "", 1, 1, 'C', $fill);
                }
                $fill = !$fill;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    $smtp = null;
    $smtp2 = null;
    $result = null;
    $result2 = null;
    $con = null;
    //Generamos el pdf
    $pdf->Output();
}
