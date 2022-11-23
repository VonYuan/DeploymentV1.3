<?php
require '../../Config.php';
require '../../fpdf/fpdf.php';
$uid = $_GET['user_id'];
$month = $_GET['month'];
$accountNum=$_GET['user_account'];

$currentmonth=date_create($month);
date_sub($currentmonth,date_interval_create_from_date_string("1 months"));
$previous_month=date_format($currentmonth,"Y-m");
 
$previous_result =  mysqli_query($link, "SELECT * FROM current_bill WHERE user_id = $uid AND month= '$previous_month' AND user_account= '$accountNum'");
$previous_data_bill = mysqli_fetch_assoc($previous_result);

$result =  mysqli_query($link, "SELECT * FROM current_bill WHERE user_id = $uid AND month= '$month' AND user_account= '$accountNum'");
$data_bill = mysqli_fetch_assoc($result);

$overduePayment= mysqli_query($link, "SELECT SUM(total) FROM current_bill WHERE user_id = $uid AND user_account= '$accountNum' AND status!='Paid'");
$overdueamount = mysqli_fetch_array($overduePayment);

$res =  mysqli_query($link, "SELECT * FROM current_details WHERE user_id = $uid AND user_account= '$accountNum'");
$data = mysqli_fetch_assoc($res);
$name = ': ' . $data['name'];
$address = ': ' . $data['user_address'];
$acc = ': ' . $data['user_account'];
$area = ': ' . $data['user_area'];
$pre = ': ' . $data['user_premises'];
$month = ': ' . $data_bill['month'];
$meter = ': ' . $data_bill['meter'].' m3';
$units = ': ' . $data_bill['units'].' m3';
$charge = ': RM ' . round($data_bill['charge_current_Month'],0) ;
#$totals = ': RM ' . $data_bill['total'] ;
$overpaidAmount=': RM 0';
$overdue=': RM '.$overdueamount[0];
$overpaid = ': RM ' . $overpaidAmount;
$due = ': ' . $data_bill['due'];
$updated = ': ' . $data_bill['updated_at'];
$amount=': RM ' . $data_bill['amount_pay'];

$overpaidAmount=': RM ' . $data_bill['credit'];
$needpay=$data_bill['charge_current_Month']+$overdueamount[0]-$data_bill['credit'];
$amountneedtopay=': RM ' . round($needpay,0);

if($previousMetercheck = empty($previous_data_bill['meter']))
   {
        $previousMeter=': ' . 0 . ' m3';
   }
      
   else
   {
       $previousMeter=$previous_data_bill['meter'];
   }
   

#$overduepayment=$total-$charge;

if($data_bill['total']<0)
{
    $absolute_total=abs($data_bill['total']);
    $overall_total=$absolute_total+$data_bill['total'];
    $total=': RM ' .  $overall_total;

    
}else
{
    $total=': RM ' . $data_bill['total'] ;
}



$pdf = new FPDF('p', 'mm', 'A4');
$pdf->AddPage();
$pdf->Rect(7, 7, 197, 287, 'D'); //For A4
$pdf->Image('../../images/petros.jpg', 10, 10, 30, 35);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Ln();
$pdf->Cell(0, 10, 'Petros Statement of Gas Account', 0, 1, 'C');
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, '  PERSONAL DETAILS', 1, 1, 'L');
$pdf->ln(6);
$pdf->SetLeftMargin(20);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, 8, 'Name', 0, 0, 'L');
$pdf->Cell(40, 8, $name, 0, 1, 'L');
$pdf->Cell(40, 8, 'Address', 0, 0, 'L');
$pdf->Cell(40, 8, $address, 0, 1, 'L');
$pdf->Cell(40, 8, 'Area Office', 0, 0, 'L');
$pdf->Cell(40, 8, $area, 0, 1, 'L');
$pdf->Cell(40, 8, 'Account Number', 0, 0, 'L');
$pdf->Cell(40, 8, $acc, 0, 1, 'L');
$pdf->Cell(40, 8, 'Premises ID', 0, 0, 'L');
$pdf->Cell(40, 8, $pre, 0, 1, 'L');
$pdf->Ln();
$pdf->Ln();
$pdf->SetLeftMargin(10);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, '  BILL DETAILS', 1, 1, 'L');
$pdf->ln(6);
$pdf->SetLeftMargin(20);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 8, 'Month', 0, 0, 'L');
$pdf->Cell(40, 8, $month, 0, 1, 'L');

$pdf->Cell(60, 8, 'Previous Month Meter', 0, 0, 'L');
$pdf->Cell(40, 8, $previousMeter, 0, 1, 'L');

$pdf->Cell(60, 8, 'Current Month Meter Reading', 0, 0, 'L');
$pdf->Cell(40, 8, $meter, 0, 1, 'L');

$pdf->Cell(60, 8, 'Units Consumed for the month', 0, 0, 'L');
$pdf->Cell(40, 8, $units, 0, 1, 'L');

$pdf->Cell(60, 8, 'Charge for the Month (RM)', 0, 0, 'L');
$pdf->Cell(40, 8, $charge, 0, 1, 'L');

$pdf->Cell(60, 8, 'Overdue Payment (RM)', 0, 0, 'L');
$pdf->Cell(40, 8, $overdue, 0, 1, 'L');

$pdf->Cell(60, 8, 'Total Amount Due (RM)', 0, 0, 'L');
$pdf->Cell(40, 8, $total, 0, 1, 'L');

$pdf->Cell(60, 8, 'Credit Amount (RM)', 0, 0, 'L');
$pdf->Cell(40, 8, $overpaidAmount, 0, 1, 'L');

$pdf->Cell(60, 8, 'Amount Need To Pay (RM)', 0, 0, 'L');
$pdf->Cell(40, 8, $amountneedtopay, 0, 1, 'L');

$pdf->Cell(60, 8, 'Amount Pay You Pay', 0, 0, 'L');
$pdf->Cell(40, 8, $amount, 0, 1, 'L');


$pdf->ln(8);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, '  ISSUING DETAILS', 1, 1, 'L');
$pdf->ln(6);
$pdf->SetLeftMargin(20);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, 8, 'Date of Issue', 0, 0, 'L');
$pdf->Cell(40, 8, $updated, 0, 1, 'L');
$pdf->Cell(40, 8, 'Pay Before', 0, 0, 'L');
$pdf->Cell(40, 8, $due, 0, 1, 'L');
$pdf->SetY(260);
$pdf->Cell(0, 10, 'Page ' . $pdf->PageNo(), 0, 0, 'C');
$pdf->Output();
