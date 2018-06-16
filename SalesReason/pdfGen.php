<?php
require("Connectdb.php");
require('fpdf.php');
    $month1 = $_POST["month"];
	$year1 = $_POST["year"];
	$month2 = $_POST["month2"];
    $year2 = $_POST["year2"];
	$month3 = $_POST["month3"];
	$year3 = $_POST["year3"];

	$Stock = $_POST['txtRPStockNo'];
	$choice = $_POST['radio'];
	
	if($choice == 1)
	{
		$sql ="SELECT L.StockNo, L.ProductCode, P.ProductName, L.QuantityOut, L.Price, L.TotalPrice ,L.SaleOrder
FROM inventoryline L JOIN inventory I ON I.StockNo=L.StockNo JOIN product P ON L.ProductCode=P.ProductCode WHERE L.StockNo = '$Stock'";
		$result = mysql_query($sql,$conn);
		
		while($data=mysql_fetch_assoc($result))
				$listData[]=$data;
				
		$sql="SELECT SUM(L.QuantityOut), SUM(L.TotalPrice) FROM inventoryline L WHERE L.StockNo = '$Stock'";
		$sumResult = mysql_query($sql,$conn);
    	list($sumQuantity,$sumTotalPrice) = mysql_fetch_row($sumResult);
		
		mysql_free_result($result);
    	mysql_free_result($sumResult);
				
$pdf=new FPDF();
//Column titles InvoiceDate,TotalAmount,VAT,AmountDue
$header=array('Product Code','Name','Quantity OUT','Price','Total Price','SaleOrder Number');
$pdf->AddPage();
$pdf->SetFont('Arial','B',20);
//report Header
//$pdf->SetTextColor(255,0,0);
$pdf->Cell(0,7,'Report Stock No :',0,0,'C');
//New line
$pdf->Ln();
$pdf->Ln(15);
$pdf->SetFont('Arial','B',15);
$pdf->Cell(0,7,'Stock No :' .$Stock,0,0,'L');
//$pdf->Cell(0,7,$Stock,0,0,'R');

//check whether it has data or not
if(count($listData)>0)
{
//set width of each column
$w=array(25,45,25,25,25,35);
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
$pdf->Cell($w[2],5,$row['QuantityOut'],L,0,'R',$fill);
$pdf->Cell($w[3],5,$row['Price'],LR,0,'R',$fill);
$pdf->Cell($w[4],5,$row['TotalPrice'],LR,0,'R',$fill);
$pdf->Cell($w[5],5,$row['SaleOrder'],LR,0,'C',$fill);


$pdf->Ln();
$fill = !$fill;
}
//Closure line
//$pdf->Ln();
$pdf->Cell(array_sum($w),0.1,'','TLR');
$pdf->Ln();
//sum line
$pdf->Cell($w[0],4,' ',L,0,'C',$fill);
$pdf->Cell($w[1],4,'Grand Total',0,0,'C',$fill);
$pdf->Cell($w[2],4,number_format($sumQuantity,0),LR,0,'R',$fill);
$pdf->Cell($w[3],4,' ',L,0,'C',$fill);
$pdf->Cell($w[4],4,number_format($sumTotalPrice,2),L,0,'R',$fill);
$pdf->Cell($w[5],4,' ',LR,0,'C',$fill);
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
		
		$sql="SELECT I.StockNo,Date,L.ProductCode,L.QuantityOut,L.Price,L.TotalPrice,L.SaleOrder FROM inventory I JOIN inventoryline L ON I.StockNo=L.StockNo WHERE (month(Date) between '$month1' and '$month2') and (year(Date) between '$year1' and '$year2') and (L.StockNo LIKE 'SL%')";
    $result = mysql_query($sql,$conn);

    while($data=mysql_fetch_assoc($result))
				$listData[]=$data;
				
		$sql="SELECT SUM(L.QuantityOut) , SUM(L.TotalPrice ) FROM inventory I JOIN inventoryline L ON I.StockNo=L.StockNo WHERE (month(Date) between '$month1' and '$month2') and (year(Date) between '$year1' and '$year2') and (L.StockNo LIKE  'SL%');";
    $sumResult = mysql_query($sql,$conn);
    list($sumQuantityOut, $sumTotalPrice) = mysql_fetch_row($sumResult);


    mysql_free_result($result);
    mysql_free_result($sumResult);

$pdf=new FPDF();
//Column titles  InvoiceDate,TotalAmount,VAT,AmountDue
$header=array('Stock No','Date','Product Code','Quantity Out','Unit Price','Total Price','Sale Order');
$pdf->AddPage();
$pdf->SetFont('Arial','B',30);
//report Header
//$pdf->SetTextColor(255,0,0);
$pdf->Cell(0,2,'Report in Month',0,0,'C');
$pdf->Ln(15);
$pdf->SetFont('Arial','B',15);
$pdf->Cell(50,7,'Month :' .$month1,0,0,'L');
$pdf->Ln(5);
$pdf->Cell(50,7,'to Month :' .$month2,0,0,'L');
//$pdf->Cell($w[0],6,'Month : ',0,0,'L',$fill);
//$pdf->Cell($w[1],6,$month1,0,0,'L',$fill);

//New line
$pdf->Ln();

//check whether it has data or not
if(count($listData)>0)
{
//set width of each column
$w=array(25,25,25,25,25,25,25);
//set font
$pdf->SetFont('Arial','',10);
$pdf->Ln(10);
    //display report header
    for($i=0;$i<count($header);$i++)
//        $pdf->Cell($w[$i],7,$header[$i],'TB',0,'C');
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
    	$pdf->Cell($w[0],6,$row['StockNo'],L,0,'C',$fill);
        $pdf->Cell($w[1],6,$row['Date'],L,0,'C',$fill);
		$pdf->Cell($w[2],6,$row['ProductCode'],L,0,'C',$fill);
        $pdf->Cell($w[3],6,$row['QuantityOut'],L,0,'R',$fill);
        $pdf->Cell($w[4],6,$row['Price'],L,0,'R',$fill);
		$pdf->Cell($w[5],6,$row['TotalPrice'],LR,0,'R',$fill);
        $pdf->Cell($w[6],6,$row['SaleOrder'],LR,0,'C',$fill);

        $pdf->Ln();
        $fill = !$fill;
    }
    //Closure line
	//$pdf->Ln();
    $pdf->Cell(array_sum($w),0.1,'','TLR');
    $pdf->Ln();
    //sum line
	$pdf->Cell($w[0],6,' ',L,0,'C',$fill);
	$pdf->Cell($w[1],6,' ',0,0,'C',$fill);
	//$pdf->Cell($w[2],6,' ',0,0,'C',$fill);
	//$pdf->Cell($w[2],6,$row['SaleOrder'],LR,0,'R',$fill);
    $pdf->Cell($w[2],6,'Grand Total',LR,0,'C',$fill);
    $pdf->Cell($w[3],6,number_format($sumQuantityOut,0),LR,0,'R',$fill);
	$pdf->Cell($w[4],6,' ',L,0,'C',$fill);
    $pdf->Cell($w[5],6,number_format($sumTotalPrice,2),LR,0,'R',$fill);
	$pdf->Cell($w[6],6,' ',LR,0,'C',$fill);
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
			$sql ="CREATE or REPLACE VIEW V1 AS SELECT MONTH( S.Date ) , L.ProductCode, SUM( L.QuantityOut ) AS  'TotalQuantityoutMonth'
FROM InventoryLine L
JOIN Inventory S ON L.StockNo = S.StockNo
WHERE MONTH( S.Date ) ='$month3'
AND L.StockNo LIKE  'SL%'
GROUP BY L.ProductCode";
			mysql_query($sql,$conn) or die(mysql_error());
		
		$sql = "SELECT V.ProductCode, P.ProductName, V.TotalQuantityoutMonth
FROM V1 V JOIN Product P ON V.ProductCode=P.ProductCode";
		$result = mysql_query($sql,$conn);	
		while($data=mysql_fetch_assoc($result))
				$listData[]=$data;
				
    	$sql="SELECT SUM(V.TotalQuantityoutMonth) FROM V1 V";
		$sumResult = mysql_query($sql,$conn);
    	list($sumTotal) = mysql_fetch_row($sumResult);
		
		mysql_free_result($result);
    	mysql_free_result($sumResult);


$pdf=new FPDF();
//Column titles InvoiceDate,TotalAmount,VAT,AmountDue
$header=array('Month','Poduct Code','Product Name','Quantity Out');
$pdf->AddPage();
$pdf->SetFont('Arial','B',20);
//report Header
//$pdf->SetTextColor(255,0,0);
$pdf->Cell(0,7,'Total of each Product in Month',0,0,'C');
//New line
$pdf->Ln();
$pdf->Ln(15);
$pdf->SetFont('Arial','B',15);
$pdf->Cell(50,7,'Month :' .$month3,0,0,'L');
//$pdf->Cell(0,7,$month3,0,0,'C');

//check whether it has data or not
if(count($listData)>0)
{
//set width of each column
$w=array(30,40,60,30);
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
$pdf->Cell($w[0],5,$month3,L,0,'C',$fill);
$pdf->Cell($w[1],5,$row['ProductCode'],L,0,'C',$fill);
$pdf->Cell($w[2],5,$row['ProductName'],L,0,'C',$fill);
//$pdf->Cell($w[3],4,$row['TotalQuantityoutMonth'],L,0,'R',$fill);
$pdf->Cell($w[3],5,$row['TotalQuantityoutMonth'],LR,0,'R',$fill);

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
$pdf->Cell($w[2],4,'Total',LR,0,'C',$fill);
$pdf->Cell($w[3],4,number_format($sumTotal,0),LR,0,'R',$fill);

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