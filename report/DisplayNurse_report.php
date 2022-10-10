<?php
require('../assets/fpdf183/fpdf.php');

$nurse_id = base64_decode($_REQUEST['nurse_id']);

class MyPdf extends FPDF
{

	///////////////////////// Make page header /////////////////////////////
	function Header()
	{
		$this->Image('../images/icons/nurse2.png', 10, 6, 30);
		$this->SetFont('Arial', 'B', 15);
		$this->Cell(200, 10, 'NURSE REPORT (INDIVIDUAL)', 0, 0, 'C');
		$this->Ln();
		$this->SetFont('Times', '', 14);
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

	///////////////////////// Page table body /////////////////////////////
	function TableBody()
	{
		global $nurse_id;

		include('../model/staff_model.php');
		$nurse_obj = new Nurse($conn);
		$result_nurse = $nurse_obj->get_SpecificNurse($nurse_id);

		include('../model/campRefers_model.php');
		$CampNurse_obj = new Nurse_camp($conn);
		$result_CampNurse = $CampNurse_obj->get_nurseByNurseID($nurse_id);;
		$row_nurse = $result_nurse->fetch_assoc();


		$this->SetFont('Times', '', 15);
		$this->Cell(50, 10, '  Nurse ID:', 1, 0, 'L');
		$this->SetFont('Arial', '', 12);
		$this->Cell(140, 10, '  ' . $row_nurse['nurse_id'], 1, 1, 'L');
		$this->SetFont('Times', '', 15);
		$this->Cell(50, 10, '  Nurse Name :', 1, 0, 'L');
		$this->SetFont('Arial', '', 12);
		$this->Cell(140, 10, '  ' . $row_nurse['fname'] . " " . $row_nurse['lname'], 1, 1, 'L');
		$this->SetFont('Times', '', 15);
		$this->Cell(50, 10, '  Registered Date :', 1, 0, 'L');
		$this->SetFont('Arial', '', 12);
		$this->Cell(140, 10, '  ' . date('Y-m-d', strtotime($row_nurse['reg_date'])), 1, 1, 'L');
		$this->SetFont('Times', '', 15);
		$this->Cell(50, 15, '  Qualifications :', 1, 0, 'L');
		$this->SetFont('Arial', '', 12);
		$this->Cell(140, 15, '  ' . $row_nurse['qualif'], 1, 1, 'L');

		$rows_count = $result_CampNurse->num_rows;

		if ($rows_count > 0) {

			$this->SetFont('Times', '', 15);
			$this->Cell(50, $rows_count * 10, '  Service History :', 1, 0, 'L');

			$x = $this->GetX();

			$this->SetFont('Arial', '', 12);
			while ($row_CampNurse = $result_CampNurse->fetch_assoc()) {

				include_once('../model/camp_model.php');
				$camp_obj = new Camp($conn);
				$result_camp = $camp_obj->get_specificCamp($row_CampNurse['camp_id']);
				$row_camp = $result_camp->fetch_assoc();

				$this->Cell(140, 10, "  Camp ID :- " . $row_CampNurse['camp_id'] . "  Date :- " . $row_camp['date'], 1, 1, 'L');
				$this->SetX($x);
			}
		} else {
			$this->SetFont('Times', '', 15);
			$this->Cell(50, 10, '  Service History :', 1, 0, 'L');
			$this->SetFont('Arial', '', 11);
			$this->Cell(140, 10, "*No Records*", 1, 1, 'C');
		}

		$this->Ln();
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
		$this->Cell(80, 8, "..............................................", "", 0, "L");
		$date = date("Y-m-d");
		$this->Cell(100, 10, $date, "", 1, "R");
		$this->Cell(80, 10, "Manager", "", 0, "L");
		$this->Cell(95, 10, "Date", "", 1, "R");
	}
}

$pdf = new MyPdf();

///////////////////////// Execute methods /////////////////////////////
$pdf->AliasNbPages();
$pdf->AddPage('P', 'A4', 0);
//$pdf->TableHeading();
$pdf->TableBody();
$pdf->Terms_Singature();

$date = date("Y-m-d");

$filename = "NurseReport_" . $nurse_id . "_" . $date . ".pdf";
//$path ="../document/userReport/$filename";

$pdf->Output('', $filename);
