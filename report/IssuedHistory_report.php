<?php
require('../assets/fpdf183/fpdf.php');

class MyPdf extends FPDF
{

    ///////////////////////// Make page header /////////////////////////////
    function Header()
    {
        $this->Image('../images/icons/schedule1.jpg', 10, 6, 30);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(200, 10, 'BLOOD ISSUED HISTORY', 0, 0, 'C');
        $this->Ln();
        $this->SetFont('Times', 'i', 14);
        $this->Cell(200, 10, 'BLOOD TRANSFUSION MANAGEMENT SYSTEM', 0, 0, 'C');
        $this->Ln(30);
    }

    ///////////////////////// Make page footer /////////////////////////////
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function TableHeading()
    {
        $this->SetFont('Times', 'B', 12);
        $this->Cell(23, 10, '#Bag ID', 1, 0, 'C');
        $this->Cell(27, 10, 'Patient NIC', 1, 0, 'C');
        $this->Cell(55, 10, 'Patient Name', 1, 0, 'C');
        $this->Cell(40, 10, 'Hospital', 1, 0, 'C');
        $this->Cell(45, 10, 'Issued Time', 1, 0, 'C');
        $this->Ln();
    }

    ///////////////////////// Page table body /////////////////////////////
    function TableBody()
    {
        include('../model/inventory_model.php');
        $inventory_obj = new Inventory($conn);
        $result = $inventory_obj->get_AllIssuedHistory();

        $this->SetFont('Times', '', 11);

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $this->Cell(23, 7, $row['bag_id'], 1, 0, 'C');

                include_once('../model/patient_model.php');
                $patient_obj = new Patient($conn);
                $result_2 = $patient_obj->get_SpecificPatient($row['patient_id']);
                $row_2 = $result_2->fetch_assoc();

                $this->Cell(27, 7, $row_2['nic_no'], 1, 0, 'C');
                $this->Cell(55, 7, $row_2['patient_name'], 1, 0, 'C');
                $this->Cell(40, 7, $row['hospital'], 1, 0, 'C');

                $issueDate = date("jS M Y g:i A", strtotime($row['issued_date']));

                $this->Cell(45, 7, $issueDate, 1, 0, 'C');
                $this->Ln();
            }
        } else {
            $this->Cell(190, 10, "No Records Found", 1, 0, 'C');
            $this->Ln();
        }
    }

    ///////////////////////// Set signature and date /////////////////////////////
    function Terms_Singature()
    {
        $this->Cell(80, 30, "", "", "1", "C");
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(80, 10, "Terms and conditions", "", "1", "L");
        $this->SetFont("Arial", "", "9");
        $this->Cell(80, 10, "*This is computer generated document ", "", "1", "L");
        $this->Cell(80, 10, " ", "", "1", "L");
        $this->Cell(80, 3, "..............................................", "", 0, "L");
        $date = date("Y-m-d");
        $this->Cell(100, 3, $date, "", 1, "R");
        $this->Cell(80, 7, "Manager", "", 0, "L");
        $this->Cell(95, 7, "Date", "", 1, "R");
    }
}

$pdf = new MyPdf();

///////////////////////// Execute methods /////////////////////////////
$pdf->AliasNbPages();
$pdf->AddPage('P', 'A4', 0);
$pdf->TableHeading();
$pdf->TableBody();
$pdf->Terms_Singature();

$date = date("y-m-d");
$d1 = $date . "";
$filename = "IssuedHistory_Report_$d1.pdf";
//$path ="../document/userReport/$filename";

$pdf->Output('', $filename);
