<?php
require('../assets/fpdf183/fpdf.php');

$patient_id = base64_decode($_REQUEST['patient_id']);

class MyPdf extends FPDF
{

	///////////////////////// Make page header /////////////////////////////
	function Header()
	{
		$this->Image('../images/icons/elder.png', 10, 6, 30);
		$this->SetFont('Arial', 'B', 15);
		$this->Cell(200, 10, 'PATIENT REPORT (INDIVIDUAL)', 0, 0, 'C');
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
		global $patient_id;

		include('../model/patient_model.php');
		$patient_obj = new Patient($conn);
		$result_patient = $patient_obj->get_SpecificPatient($patient_id);

		include('../model/inventory_model.php');
		$Inventory_obj = new Inventory($conn);
		$result_his = $Inventory_obj->get_issueHistoryByPatID($patient_id);

		$row_pat = $result_patient->fetch_assoc();

		$this->SetFont('Times', '', 15);
		$this->Cell(75, 10, 'Patient ID:', 1, 0, 'C');
		$this->SetFont('Arial', '', 12);
		$this->Cell(115, 10, $row_pat['patient_id'], 1, 1, 'C');
		$this->SetFont('Times', '', 15);
		$this->Cell(75, 10, 'Patient Name :', 1, 0, 'C');
		$this->SetFont('Arial', '', 12);
		$this->Cell(115, 10, $row_pat['patient_name'], 1, 1, 'C');
		$this->SetFont('Times', '', 15);
		$this->Cell(75, 10, 'Blood Group :', 1, 0, 'C');
		$this->SetFont('Arial', '', 12);
		$this->Cell(115, 10, $row_pat['grp_name'], 1, 1, 'C');

		$rows_count = $result_his->num_rows;

		if ($rows_count > 0) {

			$this->SetFont('Times', '', 15);
			$this->Cell(75, $rows_count * 10, 'Blood Recieved History :', 1, 0, 'C');

			$x = $this->GetX();

			$this->SetFont('Arial', '', 11);
			while ($row_his = $result_his->fetch_assoc()) {

				$this->Cell(115, 10, $row_his['issued_date'] . " - " . $row_his['issue_count'] . " Bags Via " . $row_his['hospital'], 1, 1, 'C');
				$this->SetX($x);
			}
		} else {
			$this->SetFont('Times', '', 15);
			$this->Cell(75, 10, 'Blood Recieved History :', 1, 0, 'C');
			$this->SetFont('Arial', '', 11);
			$this->Cell(115, 10, "*No Records*", 1, 1, 'C');
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

$filename = "PatientReport_" . $patient_id . "_" . $date . ".pdf";
//$path ="../document/userReport/$filename";

$pdf->Output('', $filename);
