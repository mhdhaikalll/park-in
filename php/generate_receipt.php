<?php
if (!isset($_GET['transac_id']) || !isset($_GET['booking_id']) || !isset($_GET['amount'])) {
    echo "Missing parameters.";
    exit();
}

$transac_id = $_GET['transac_id'];
$booking_id = $_GET['booking_id'];
$amount = $_GET['amount'];

$update_query = "UPDATE transaction
                    SET status = 'paid'
                    WHERE transac_id = ?";

// Generate PDF
require_once('../TCPDF-main/tcpdf.php');

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('PARK-iN');
$pdf->SetTitle('Payment Receipt');
$pdf->SetSubject('Receipt for Booking Payment');
$pdf->SetKeywords('TCPDF, PDF, receipt, booking, payment');

// Set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'PARK-iN Payment Receipt', "Transaction ID: $transac_id\nGenerated on: " . date('Y-m-d'), array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add a page
$pdf->AddPage();

// HTML content
$html = '
<h1 style="text-align: center;">Payment Receipt</h1>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th style="width: 30%; background-color: #f2f2f2;">Transaction ID</th>
        <td style="width: 70%;">' . $transac_id . '</td>
    </tr>
    <tr>
        <th style="background-color: #f2f2f2;">Booking ID</th>
        <td>' . $booking_id . '</td>
    </tr>
    <tr>
        <th style="background-color: #f2f2f2;">Amount</th>
        <td>RM ' . number_format($amount, 2) . '</td>
    </tr>
    <tr>
        <th style="background-color: #f2f2f2;">Date</th>
        <td>' . date('Y-m-d') . '</td>
    </tr>
</table>
<br /><br />
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th style="width: 50%; background-color: #f2f2f2;">Description</th>
            <th style="width: 50%; background-color: #f2f2f2;">Details</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Parking Lot Booking</td>
            <td>Booking ID: ' . $booking_id . '</td>
        </tr>
        <tr>
            <td>Total Amount</td>
            <td>RM ' . number_format($amount, 2) . '</td>
        </tr>
    </tbody>
</table>
<br /><br />
<p>Thank you for your payment. If you have any questions, please contact our support team.</p>
<p><strong>cs@parkin.my</strong></p>
';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('receipt.pdf', 'D');
exit();
