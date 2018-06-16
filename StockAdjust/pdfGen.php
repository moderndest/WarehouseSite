<?php
require("Connectdb.php");
require('fpdf.php');
$month1 = $_POST["month"];
$year1 = $_POST["year"];
$month2 = $_POST["month2"];
$year2 = $_POST["year2"];
$month3 = $_POST["month3"];
$year3 = $_POST["year3"];
$month4 = $_POST["month4"];
$year4 = $_POST["year4"];

$Stock = $_POST['txtRPStockNo'];
$choice = $_POST['radio'];

if($choice == 1)
{

$sql ="SELECT P.ProductCode,ProductName, Quantity,Adjust
FROM InventoryLine L JOIN Product P ON L.ProductCode = P.ProductCode WHERE StockNo = '$Stock'";

$result = mysql_query($sql,$conn);

while($data=mysql_fetch_assoc($result))
$listData[]=$data;

//$sql="SELECT sum(QuantityIn), sum(QuantityOut) ".
//			"FROM InventoryLine L JOIN Inventory I ON L.StockNo = I.StockNo ".
//			"WHERE (month(Date) between '$month1' and '$month2') and (year(Date) between '$year1' and '$year2') and I.StockNo like '%AJ%'";
//  $sumResult = mysql_query($sql,$conn);
//    list($QuantityIn, $QuantityOut) = mysql_fetch_row($sumResult);

mysql_free_result($result);
//mysql_free_result($sumResult);

$pdf=new FPDF();
//Column titles InvoiceDate,TotalAmount,VAT,AmountDue
$header=array('Stock No','Product Code','Product Name','Quantity','Adjust');
$pdf->AddPage();
$pdf->SetFont('Arial','B',30);
//report Header
//$pdf->SetTextColor(255,0,0);
$pdf->Cell(0,7,'REPORT FORM STOCK NO',0,0,'C');
//New line
$pdf->Ln();

//check whether it has data or not
if(count($listData)>0)
{
//set width of each column
$w=array(30,40,55,30,30);
//set font
$pdf->SetFont('Arial','',10);
$pdf->Ln(10);
//display report header
for($i=0;$i<count($header);$i++)
// $pdf->Cell($w[$i],7,$header[$i],'TB',0,'C');
$pdf->Cell($w[$i],7,$header[$i],'TBLR',0,'C');
$pdf->Ln();
$pdf->Cell(array_sum($w),0.1,'','TLR');
$pdf->Ln();
//Data
$pdf->SetFillColor(224,235,255);
$fill = 'false';
//show each invoice detail
foreach($listData as $row)
{
$pdf->Cell($w[0],4,$Stock,L,0,'C',$fill);
$pdf->Cell($w[1],4,$row['ProductCode'],L,0,'C',$fill);
$pdf->Cell($w[2],4,$row['ProductName'],L,0,'C',$fill);
$pdf->Cell($w[3],4,$row['Quantity'],L,0,'C',$fill);
$pdf->Cell($w[4],4,$row['Adjust'],LR,0,'C',$fill);


$pdf->Ln();
$fill = !$fill;
}
//Closure line
//$pdf->Ln();
$pdf->Cell(array_sum($w),0.1,'','TLR');
$pdf->Ln();
//sum line
//$pdf->Cell($w[0],4,' ',L,0,'C',$fill);
//$pdf->Cell($w[1],4,' ',0,0,'C',$fill);
//$pdf->Cell($w[2],4,'Grand Total',LR,0,'C',$fill);
//$pdf->Cell($w[3],4,number_format($QuantityIn,0),LR,0,'R',$fill);
//$pdf->Cell($w[4],4,number_format($QuantityOut,0),LR,0,'R',$fill);
//$pdf->Cell($w[6],6,number_format($sumAmountDue,2),LR,0,'R',$fill);
$pdf->Ln();
//Closure line
$pdf->Cell(array_sum($w),0.1,'','BLR');
}
else{
//if no data found
$pdf->Ln(4);
$pdf->SetTextColor(255,0,0);
$pdf->Cell(0,7,'No Data Found!!!',0,0,'L');

}
//generate PDF output
$pdf->Output();

}
else if($choice == 2)
{

$sql="SELECT I.StockNo,Date,L.ProductCode,L.Quantity FROM inventory I JOIN inventoryline L ON I.StockNo=L.StockNo WHERE (month(Date) between '$month1' and '$month2') and (year(Date) between '$year1' and '$year2') and (L.StockNo LIKE 'SB%')";
$result = mysql_query($sql,$conn);

while($data=mysql_fetch_assoc($result))
$listData[]=$data;

$sql="SELECT sum(Quantity) ".
			"FROM InventoryLine L JOIN Inventory I ON L.StockNo = I.StockNo ".
			"WHERE (month(Date) between '$month1' and '$month2') and (year(Date) between '$year1' and '$year2') and I.StockNo like '%SB%'";
    $sumResult = mysql_query($sql,$conn);
    list($Quantity) = mysql_fetch_row($sumResult);

mysql_free_result($result);
mysql_free_result($sumResult);

$pdf=new FPDF();
//Column titles InvoiceDate,TotalAmount,VAT,AmountDue
$header=array('Stock No','Date','Product Code','Quantity');
$pdf->AddPage();
$pdf->SetFont('Arial','B',20);
//report Header
//$pdf->SetTextColor(255,0,0);
$pdf->Cell(0,7,' STOCK BEGINNING BY MONTH AND YEAR',0,0,'C');
//New line
$pdf->Ln();

//check whether it has data or not
if(count($listData)>0)
{
//set width of each column
$w=array(45,45,45,45);
//set font
$pdf->SetFont('Arial','',10);
$pdf->Ln(10);
//display report header
for($i=0;$i<count($header);$i++)
// $pdf->Cell($w[$i],7,$header[$i],'TB',0,'C');
$pdf->Cell($w[$i],7,$header[$i],'TBLR',0,'C');
$pdf->Ln();
$pdf->Cell(array_sum($w),0.1,'','TLR');
$pdf->Ln();
//Data
$pdf->SetFillColor(224,235,255);
$fill = 'false';
//show each invoice detail
foreach($listData as $row)
{
$pdf->Cell($w[0],5,$row['StockNo'],L,0,'C',$fill);
$pdf->Cell($w[1],5,$row['Date'],L,0,'C',$fill);
$pdf->Cell($w[2],5,$row['ProductCode'],L,0,'C',$fill);
$pdf->Cell($w[3],5,$row['Quantity'],LR,0,'R',$fill);

$pdf->Ln();
$fill = !$fill;
}
//Closure line
//$pdf->Ln();
$pdf->Cell(array_sum($w),0.1,'','TLR');
$pdf->Ln();
//sum line
$pdf->Cell($w[0],4,' ',L,0,'C',$fill);
$pdf->Cell($w[1],4,' ',0,0,'C',$fill);
$pdf->Cell($w[2],4,'Grand Total',L,0,'C',$fill);
$pdf->Cell($w[3],4,number_format($Quantity,0),LR,0,'R',$fill);
//$pdf->Cell($w[6],6,number_format($sumAmountDue,2),LR,0,'R',$fill);
$pdf->Ln();
//Closure line
$pdf->Cell(array_sum($w),0.1,'','BLR');
}
else{
//if no data found
$pdf->Ln(4);
$pdf->SetTextColor(255,0,0);
$pdf->Cell(0,7,'No Data Found!!!',0,0,'L');

}
//generate PDF output
$pdf->Output();
}
else if($choice == 3)
{
$sql="SELECT P.ProductCode,P.ProductName,L.Quantity,Adjust FROM Product P JOIN inventoryline L ON P.ProductCode = L.ProductCode JOIN Inventory I ON I.StockNo = L.StockNo WHERE month(Date) ='$month3' and year(Date) ='$year3' and (L.StockNo LIKE 'SB%')";
$result = mysql_query($sql,$conn);

while($data=mysql_fetch_assoc($result))
$listData[]=$data;

$sql="SELECT sum(Quantity), sum(Adjust) ".
			"FROM InventoryLine L JOIN Inventory I ON L.StockNo = I.StockNo ".
			"WHERE month(Date) = '$month3' and year(Date) ='$year3' and I.StockNo like '%SB%'";
    $sumResult = mysql_query($sql,$conn);
    list($Quantity, $Adjust) = mysql_fetch_row($sumResult);

mysql_free_result($result);
mysql_free_result($sumResult);

$pdf=new FPDF();
//Column titles InvoiceDate,TotalAmount,VAT,AmountDue
$header=array('Product Code','Poduct Name','Quantity','Adjust');
$pdf->AddPage();
$pdf->SetFont('Arial','B',20);
//report Header
//$pdf->SetTextColor(255,0,0);
$pdf->Cell(0,7,'REBORT STOCK BEGINNING ',0,0,'C');
//New line
$pdf->Ln();

//check whether it has data or not
if(count($listData)>0)
{
//set width of each column
$w=array(40,65,45,45);
//set font
$pdf->SetFont('Arial','',10);
$pdf->Ln(10);
//display report header
for($i=0;$i<count($header);$i++)
// $pdf->Cell($w[$i],7,$header[$i],'TB',0,'C');
$pdf->Cell($w[$i],7,$header[$i],'TBLR',0,'C');
$pdf->Ln();
$pdf->Cell(array_sum($w),0.1,'','TLR');
$pdf->Ln();
//Data
$pdf->SetFillColor(224,235,255);
$fill = 'false';
//show each invoice detail
foreach($listData as $row)
{
$pdf->Cell($w[0],5,$row['ProductCode'],L,0,'C',$fill);
$pdf->Cell($w[1],5,$row['ProductName'],L,0,'C',$fill);
$pdf->Cell($w[2],5,$row['Quantity'],L,0,'R',$fill);
$pdf->Cell($w[3],5,$row['Adjust'],LR,0,'R',$fill);

$pdf->Ln();
$fill = !$fill;
}
//Closure line
//$pdf->Ln();
$pdf->Cell(array_sum($w),0.1,'','TLR');
$pdf->Ln();
//sum line
$pdf->Cell($w[0],4,' ',L,0,'C',$fill);
$pdf->Cell($w[1],4,'Grand Total',L,0,'C',$fill);
$pdf->Cell($w[2],4,number_format($Quantity,0),L,0,'R',$fill);
$pdf->Cell($w[3],4,number_format($Adjust,0),LR,0,'R',$fill);
//$pdf->Cell($w[6],6,number_format($sumAmountDue,2),LR,0,'R',$fill);
$pdf->Ln();
//Closure line
$pdf->Cell(array_sum($w),0.1,'','BLR');
}
else{
//if no data found
$pdf->Ln(4);
$pdf->SetTextColor(255,0,0);
$pdf->Cell(0,7,'No Data Found!!!',0,0,'L');

}
//generate PDF output
$pdf->Output();
}
?>