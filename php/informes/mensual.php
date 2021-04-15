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
            if ($fecha == "1970/01/01") {
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
                    $fecha = date("m/Y", strtotime(filter_input(INPUT_GET, 'fecha')));
                    if ($fecha == "01/1970") {
                        $fecha = date("m/Y", getdate()[0]);
                    }
                } else {
                    $fecha = date("m/Y", getdate()[0]);
                }
            } else {
                $fecha = date("m/Y", getdate()[0]);
            }
            $w = array(10, 60, 30, 30, 30, 30);
            // Logo
            $this->Image('../../assets/img/sistema/logo.png', 10, 4, 15);
            // Arial bold 15
            $this->SetFont('Arial', 'B', 15);
            // Movernos a la derecha
            $this->Cell(50);
            // Título
            $this->Cell(90, 10, 'INFORME MENSUAL DE ' . utf8_decode($fecha), 1, 0, 'C');
            // Salto de línea
            $this->Ln(15);
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
        $sql = "SELECT idUsuario, nombre, apellido1, apellido2  FROM usuarios WHERE f_baja IS NULL OR (MONTH(f_baja) >= " . date('m', strtotime($fecha)) . " AND YEAR(f_baja) >= " . date('Y', strtotime($fecha)) . ")  ORDER BY apellido1, apellido2, nombre";

        $stmt = $con->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        try {
            $fill = false;
            foreach ($result as $trabajador) {
                $pdf->SetFillColor(224, 235, 255);
                $pdf->Cell(190, 10, utf8_decode($trabajador['apellido1'] . " " . $trabajador['apellido2'] . ", " . $trabajador['nombre']), 1, 1, 'L', $fill);
                //Hacemos la consulta para conseguir los horarios del trabajador del mes seleccionado
                $sql = "SELECT hora, tipo, observaciones FROM registros WHERE idUsuario = " . $trabajador['idUsuario'] . " AND MONTH(CAST(hora AS DATE))=" . date('m', strtotime($fecha)) . " AND YEAR(CAST(hora AS DATE))=" . date('Y', strtotime($fecha));
                $stmt2 = $con->prepare($sql);

                $stmt2->execute();

                $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result2 as $registro) {
                    $pdf->SetFillColor(200, 0, 0);
                    $pdf->Cell(25, 10, "", 0, 0, 'C', 0);
                    $pdf->Cell(45, 10, utf8_decode($registro['hora']), 1, 0, 'C', 0);
                    if ($registro['tipo'] == "entrada" && $registro['observaciones'] == "normal") {
                        $pdf->Cell(15, 10, utf8_decode("E"), 1, 0, 'C', 0);
                    } elseif ($registro['tipo'] == "salida" && $registro['observaciones'] == "normal") {
                        $pdf->Cell(15, 10, utf8_decode("S"), 1, 0, 'C', 0);
                    } elseif ($registro['tipo'] == "entrada") {
                        $pdf->Cell(15, 10, utf8_decode("E"), 1, 0, 'C', 1);
                    } elseif ($registro['tipo'] == "salida") {
                        $pdf->Cell(15, 10, utf8_decode("S"), 1, 0, 'C', 1);
                    }
                    $pdf->Cell(35, 10, utf8_decode($registro['observaciones']), 1, 1, 'C', 0);
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
