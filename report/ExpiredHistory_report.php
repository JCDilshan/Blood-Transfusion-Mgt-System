<?php
require('../assets/fpdf183/fpdf.php');

class MyPdf extends FPDF
{

    ///////////////////////// Make page header /////////////////////////////
    function Header()
    {
        $this->Image('../images/icons/schedule_clock.png', 10, 6, 30);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(200, 10, 'EXPIRED BLOOD BAGS', 0, 0, 'C');
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
        $this->Cell(30, 10, '#Bag ID', 1, 0, 'C');
        $this->Cell(30, 10, 'Blood Group', 1, 0, 'C');
        $this->Cell(70, 10, 'Component Type', 1, 0, 'C');
        $this->Cell(60, 10, 'Expired Date', 1, 0, 'C');
        $this->Ln();
    }

    ///////////////////////// Page table body /////////////////////////////
    function TableBody()
    {
        include_once('../model/inventory_model.php');
        $inventory_obj = new Inventory($conn);
        $result = $inventory_obj->get_ExpiredHistory();

        $this->SetFont('Times', '', 11);

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $this->Cell(30, 7, $row['bag_id'], 1, 0, 'C');

                include_once('../model/blood_model.php');
                $grp_obj = new Blood_Grp($conn);
                $comp_obj = new Blood_Component($conn);

                $grp_result = $grp_obj->give_SpecificGroup($row["blood_grp"]);
                $grp_row = $grp_result->fetch_assoc();

                $comp_result = $comp_obj->give_SpecificComp($row["comp_type"]);
                $comp_row = $comp_result->fetch_assoc();

                $this->Cell(30, 7, $grp_row["grp_name"], 1, 0, 'C');
                $this->Cell(70, 7, $comp_row["comp_name"], 1, 0, 'C');

                $expDate = date("jS M Y", strtotime($row['expired_date']));

                $this->Cell(60, 7, $expDate, 1, 0, 'C');
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
$filename = "ExpiredHistory_Report_$d1.pdf";
//$path ="../document/userReport/$filename";

$pdf->Output('', $filename);
