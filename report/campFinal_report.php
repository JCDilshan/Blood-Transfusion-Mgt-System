<?php
require('../assets/fpdf183/fpdf.php');

///////////////////////// Get camp ID /////////////////////////////
$camp_id = isset($_REQUEST['camp_id']) ? $_REQUEST['camp_id'] : '';

class MyPdf extends FPDF
{

	///////////////////////// Make page header /////////////////////////////
	function Header()
	{

		if ($this->PageNo() === 1) {
			$this->Image('../images/icons/report2.jpg', 10, 6, 30);
			$this->SetFont('Arial', 'B', 15);
			$this->Cell(200, 10, 'BLOOD CAMP FINAL REPORT', 0, 0, 'C');
			$this->Ln();
			$this->SetFont('Times', '', 14);
			$this->Cell(200, 10, 'BLOOD TRANSFUSION MANAGEMENT SYSTEM', 0, 0, 'C');
			$this->Ln(30);
		}
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
		global $camp_id;

		include('../model/camp_model.php');
		$camp_object = new Camp($conn);

		include('../model/campRefers_model.php');
		$doc_campObj = new Doctor_camp($conn);
		$nurse_campObj = new Nurse_camp($conn);
		$mem_campObj = new Member_camp($conn);

		include('../model/staff_model.php');
		$doctor_object = new Doctor($conn);
		$nurse_object  = new Nurse($conn);
		$member_object = new Other_Staff($conn);

		$result = $camp_object->get_specificCamp($camp_id);

		$docCamp_result = $doc_campObj->get_doc($camp_id);

		$nurseCamp_result = $nurse_campObj->get_nurse($camp_id);

		$memCamp_result = $mem_campObj->get_mem($camp_id);


		$row = $result->fetch_assoc();

		$this->SetFont('Times', '', 15);
		$this->Cell(75, 10, 'Camp ID :', 1, 0, 'C');
		$this->SetFont('Arial', '', 12);
		$this->Cell(115, 10, "     " . $row['camp_id'], 1, 1, 'L');
		$this->SetFont('Times', '', 15);
		$this->Cell(75, 10, 'Organizer :', 1, 0, 'C');
		$this->SetFont('Arial', '', 12);
		$this->Cell(115, 10, "     " . $row['organizer_name'], 1, 1, 'L');
		$this->SetFont('Times', '', 15);
		$this->Cell(75, 10, 'Location :', 1, 0, 'C');
		$this->SetFont('Arial', '', 12);
		$this->Cell(115, 10, "     " . $row['location'], 1, 1, 'L');
		$this->SetFont('Times', '', 15);
		$this->Cell(75, 10, 'Date & Time :', 1, 0, 'C');
		$this->SetFont('Arial', '', 12);
		$this->Cell(115, 10, "     " . $row['date'] . " At " . $row['start_time'], 1, 1, 'L');
		$this->SetFont('Times', '', 15);
		$this->Cell(75, 10, 'Expected Donors :', 1, 0, 'C');
		$this->SetFont('Arial', '', 12);
		$this->Cell(115, 10, "     " . $row['target_donors'], 1, 1, 'L');
		$this->SetFont('Times', '', 15);
		$this->Cell(75, 10, 'Participated Donors :', 1, 0, 'C');
		$this->SetFont('Arial', '', 12);
		$this->Cell(115, 10, "     " . $row['partici_donors'], 1, 1, 'L');
		$this->SetFont('Times', '', 15);
		$this->Cell(75, 10, 'Other Info :', 1, 0, 'C');
		$this->SetFont('Arial', '', 12);
		$this->Cell(115, 10, "     " . $row['other_info'], 1, 1, 'L');


		$doc_count = $docCamp_result->num_rows;

		$this->SetFont('Times', '', 15);
		$this->Cell(75, $doc_count * 10, 'Assigned Doctors :', 1, 0, 'C');

		$x = $this->GetX();

		$this->SetFont('Arial', '', 11);
		while ($docCamp_array = $docCamp_result->fetch_assoc()) {

			$doc_id = $docCamp_array['doc_id'];

			$docName_result = $doctor_object->get_Specificdoctor($doc_id);
			$docName_array  = $docName_result->fetch_assoc();

			$this->Cell(115, 10, "     #" . $doc_id . " Dr. " . $docName_array['fname'] . " " . $docName_array['lname'], 1, 1, 'L');
			$this->SetX($x);
		}

		$this->SetX(10);

		$nurse_count = $nurseCamp_result->num_rows;

		$this->SetFont('Times', '', 15);
		$this->Cell(75, $nurse_count * 10, 'Assigned Nurse :', 1, 0, 'C');

		$x = $this->GetX();

		$this->SetFont('Arial', '', 11);
		while ($nurseCamp_array = $nurseCamp_result->fetch_assoc()) {

			$nurse_id = $nurseCamp_array['nurse_id'];

			$nurseName_result = $nurse_object->get_SpecificNurse($nurse_id);
			$nurseName_array  = $nurseName_result->fetch_assoc();

			$this->Cell(115, 10, "     #" . $nurse_id . " Mrs. " . $nurseName_array['fname'] . " " . $nurseName_array['lname'], 1, 1, 'L');
			$this->SetX($x);
		}

		$this->SetX(10);

		$member_count = $memCamp_result->num_rows;

		$this->SetFont('Times', '', 15);
		$this->Cell(75, $member_count * 10, 'Assigned Other Staff :', 1, 0, 'C');

		$x = $this->GetX();

		$this->SetFont('Arial', '', 11);
		while ($memCamp_array = $memCamp_result->fetch_assoc()) {

			$mem_id = $memCamp_array['mem_id'];

			$memName_result = $member_object->get_SpecificMember($mem_id);
			$memName_array  = $memName_result->fetch_assoc();

			$this->Cell(115, 10, "     #" . $mem_id . " " . $memName_array['fname'] . " " . $memName_array['lname'], 1, 1, 'L');
			$this->SetX($x);
		}

		$this->SetX(10);

		$this->SetFont('Times', '', 15);
		$this->Cell(75, 10, 'Camp Remark :', 1, 0, 'C');
		$this->SetFont('Arial', '', 12);
		$this->Cell(115, 10, "     " . $row["remark"], 1, 1, 'L');
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
$pdf->AddPage('P', "A4", 0);
$pdf->SetAutoPageBreak(false);
//$pdf->TableHeading();
$pdf->TableBody();
$pdf->Terms_Singature();

$date = date("Y-m-d");

$filename = "CampReport_" . $camp_id . "_" . $date . ".pdf";
//$path ="../document/userReport/$filename";

$pdf->Output('', $filename);
