<?php
require_once("conn.php");
require('PDF/fpdf.php');

//$consulta = "SELECT * FROM pdftest";
$consulta = "select o.id, o.fecha, e.descripcion as extruder, c.descripcion as chorro, o.turno, o.kilos, f.nombre,m.codigo,m.descrip from ordenproduccion o
INNER JOIN formula f ON f.id = o.mezcla
INNER JOIN chorro c ON c.id = o.extruder
INNER JOIN extruder e ON e.id = c.extruder
INNER JOIN ordenrollo oro ON oro.orden = o.id
INNER JOIN material m ON m.matid = oro.rollo
WHERE o.fecha = '2023-11-18'";
$resultado = $conn->query($consulta);



class PDF extends FPDF
{
    
    // Cabecera de página
    function Header()
    {
        $this->Image('logo.jpg',20,-5,33);
       // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Movernos a la derecha
        $this->Cell(120);
        // Título
        $this->SetFont('Arial','B',16);
        $this->Cell(30,10,'CONTROL DE EXTRUSION',0,0,'C');
        $this->Cell(60);
        $this->cell(30, 10, "532",0,1, "R");

        //Linea horizontal
        $this->SetDrawColor(0,250,0);
        $this->cell(280, 0, "",1,1);
        $this->SetDrawColor(0,0,0);

        $this->SetFont('Arial','B',16);
        // Salto de línea
        $this->Ln(6);
        $this->SetFont('Arial','B',16);
        $this->cell(25, 10, "Fecha",0,0);
        $this->cell(20, 10, "15",1,0, "C");
        $this->cell(20, 10, "12",1,0, "C");
        $this->cell(20, 10, "2023",1,0, "C");
        $this->cell(140, 10, "",0,0, "C");

        $this->cell(30, 10, "Turno 1",1,1, "C");
        $this->SetFont('Arial','',10);
        $this->cell(20, 4, "",0,1);
        
        $this->SetFont('Arial','',12);
        $this->cell(25, 10, "OPERARIO",1,0);
        $this->cell(60, 10, "Jose David Angarita",1,0);
        $this->cell(85, 10, "",0,0, "C");
        $this->cell(25, 10, "AYUDANTE",1,0);
        $this->cell(60, 10, "Yan Carlos Angarita",1,1);
        $this->SetFont('Arial','',10);
        
        $this->cell(10, 5, "",0,1);
        $this->SetDrawColor(0,250,0);
        $this->cell(280, 0, "",1,1);
        $this->cell(20, 5, "",0,1);
    }
        
        
    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$pdf = new PDF("L", "mm", "A4");

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',16);

while($row = $resultado->fetch_assoc()){
    $pdf->SetFont('Arial','B',8);
    $pdf->cell(30, 10, $row["extruder"],1,0);
    $pdf->cell(20, 10, $row["chorro"],1,0);
    $pdf->cell(20, 10, "Codigo",1,0, "C");
    $pdf->SetFont('Arial','',8);
    $pdf->cell(40, 10, $row["codigo"],1,0,"C");
    $pdf->SetFont('Arial','B',8);
    $pdf->cell(20, 10, "Articulo",1,0, "C");
    $pdf->SetFont('Arial','',8);
    $pdf->cell(90, 10, $row["descrip"],1,0, "C");
    $pdf->SetFont('Arial','B',8);
    $pdf->cell(20, 10, "Kilos",1,0, "C");
    $pdf->SetFont('Arial','',8);
    $pdf->cell(40, 10, $row["kilos"] . "KG",1,1, "C");

    $pdf->cell(1, 1, "",0,1);
}

$pdf->Output();
?>