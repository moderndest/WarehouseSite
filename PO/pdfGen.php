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
		$sql ="SELECT L.StockNo, L.ProductCode, P.ProductName, L.QuantityIn,L.QuantityOut, L.Price, L.TotalPrice ,L.PurchaseOrder,L.ManufactureOrder,L.WorkOrder	
FROM inventoryline L JOIN inventory I ON I.StockNo=L.StockNo JOIN product P ON L.ProductCode=P.ProductCode WHERE L.StockNo = '$Stock'";
		$result = mysql_query($sql,$conn);
		
		while($data=mysql_fetch_assoc($result))
				$listData[]=$data;
				
		$sql="SELECT SUM(L.QuantityIn),SUM(L.QuantityOut), SUM(L.TotalPrice) FROM inventoryline L WHERE L.StockNo = '$Stock'";
		$sumResult = mysql_query($sql,$conn);
    	list($sumQuantityIn,$sumQuantityOut,$sumTotalPrice) = mysql_fetch_row($sumResult);
		
		mysql_free_result($result);
    	mysql_free_result($sumResult);
				
$pdf=new FPDF();
//Column titles InvoiceDate,TotalAmount,VAT,AmountDue
$header=array('Product Code','Name','Quantity IN','Quantity Out','Price','Total Price','PO','WO','MO');
$pdf->AddPage();
$pdf->SetFont('Arial','B',20);
//report Header
//$pdf->SetTextColor(255,0,0);
$pdf->Cell(0,7,'Report Stock No :',0,0,'C');
//New line
$pdf->Ln();
$pdf->Ln(15);
$pdf->SetFont('Arial','B',15);
$pdf->Cell(0,7,'Stock No :',0,0,'L');
$pdf->Cell(0,7,$Stock,0,0,'R');

//check whether it has data or not
if(count($listData)>0)
{
//set width of each column
$w=array(20,35,17,20,20,20,15,15,15);
//set font
$pdf->SetFont('Arial','',8);
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
$pdf->Cell($w[2],5,$row['QuantityIn'],L,0,'R',$fill);
$pdf->Cell($w[3],5,$row['QuantityOut'],L,0,'R',$fill);
$pdf->Cell($w[4],5,$row['Price'],LR,0,'R',$fill);
$pdf->Cell($w[5],5,$row['TotalPrice'],LR,0,'R',$fill);
$pdf->Cell($w[6],5,$row['PurchaseOrder'],L,0,'C',$fill);
$pdf->Cell($w[7],5,$row['WorkOrder'],L,0,'C',$fill);
$pdf->Cell($w[8],5,$row['ManufactureOrder'],LR,0,'C',$fill);


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
$pdf->Cell($w[2],4,number_format($sumQuantityIn,0),LR,0,'R',$fill);
$pdf->Cell($w[3],4,number_format($sumQuantityOut,0),LR,0,'R',$fill);
$pdf->Cell($w[4],4,' ',L,0,'C',$fill);
$pdf->Cell($w[5],4,number_format($sumTotalPrice,2),L,0,'R',$fill);
$pdf->Cell($w[6],4,' ',LR,0,'C',$fill);
$pdf->Cell($w[7],4,' ',LR,0,'C',$fill);
$pdf->Cell($w[8],4,' ',LR,0,'C',$fill);
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
		
		$sql="SELECT I.StockNo,Date,L.ProductCode,L.QuantityIn,L.Price,L.TotalPrice,L.PurchaseOrder,L.ManufactureOrder,L.WorkOrder FROM inventory I JOIN inventoryline L ON I.StockNo=L.StockNo WHERE (month(Date) between '$month1' and '$month2') and (year(Date) between '$year1' and '$year2') and (L.StockNo = '$Stock')";
    $result = mysql_query($sql,$conn);

    while($data=mysql_fetch_assoc($result))
				$listData[]=$data;
				
		$sql="SELECT SUM(L.QuantityIn),SUM(L.QuantityOut) , SUM(L.TotalPrice ) FROM inventory I JOIN inventoryline L ON I.StockNo=L.StockNo WHERE (month(Date) between '$month1' and '$month2') and (year(Date) between '$year1' and '$year2') and (L.StockNo = '$Stock');";
    $sumResult = mysql_query($sql,$conn);
    list($sumQuantityIn,$sumQuantityOut, $sumTotalPrice) = mysql_fetch_row($sumResult);


    mysql_free_result($result);
    mysql_free_result($sumResult);

$pdf=new FPDF();
//Column titles  InvoiceDate,TotalAmount,VAT,AmountDue
$header=array('Product Code','Quantity IN','Quantity Out','Price','Total Price','PO','WO','MO');
$pdf->AddPage();
$pdf->SetFont('Arial','B',30);
//report Header
//$pdf->SetTextColor(255,0,0);
$pdf->Cell(0,2,'Report in Month',0,0,'C');
$pdf->Ln(15);
$pdf->SetFont('Arial','B',8);
//$pdf->Cell($w[0],6,'Month : ',0,0,'L',$fill);
//$pdf->Cell($w[1],6,$month1,0,0,'L',$fill);

//New line
$pdf->Ln();

//check whether it has data or not
if(count($listData)>0)
{
//set width of each column
$w=array(25,25,25,25,25,15,15,15);
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
    	$pdf->Cell($w[0],5,$row['ProductCode'],L,0,'C',$fill);
		$pdf->Cell($w[1],5,$row['QuantityIn'],L,0,'R',$fill);
		$pdf->Cell($w[2],5,$row['QuantityOut'],L,0,'R',$fill);
		$pdf->Cell($w[3],5,$row['Price'],LR,0,'R',$fill);
		$pdf->Cell($w[4],5,$row['TotalPrice'],LR,0,'R',$fill);
		$pdf->Cell($w[5],5,$row['PurchaseOrder'],L,0,'C',$fill);
		$pdf->Cell($w[6],5,$row['WorkOrder'],L,0,'C',$fill);
		$pdf->Cell($w[7],5,$row['ManufactureOrder'],LR,0,'C',$fill);

        $pdf->Ln();
        $fill = !$fill;
    }
    //Closure line
	//$pdf->Ln();
    $pdf->Cell(array_sum($w),0.1,'','TLR');
    $pdf->Ln();
    //sum line
	//$pdf->Cell($w[1],6,' ',0,0,'C',$fill);
	//$pdf->Cell($w[2],6,' ',0,0,'C',$fill);
	//$pdf->Cell($w[2],6,$row['SaleOrder'],LR,0,'R',$fill);
	$pdf->Cell($w[0],4,'Grand Total',L,0,'C',$fill);
	$pdf->Cell($w[1],4,number_format($sumQuantityIn,0),LR,0,'R',$fill);
	$pdf->Cell($w[2],4,number_format($sumQuantityOut,0),LR,0,'R',$fill);
	$pdf->Cell($w[3],4,' ',L,0,'C',$fill);
	$pdf->Cell($w[4],4,number_format($sumTotalPrice,2),L,0,'R',$fill);
	$pdf->Cell($w[5],4,' ',LR,0,'C',$fill);
	$pdf->Cell($w[6],4,' ',LR,0,'C',$fill);
	$pdf->Cell($w[7],4,' ',LR,0,'C',$fill);
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
			$sql ="CREATE or REPLACE VIEW V1 AS SELECT MONTH( S.Date ) , L.ProductCode, SUM( L.QuantityIn ) AS  'TotalQuantityIninMonth', SUM( L.QuantityOut ) AS  'TotalQuantityOutinMonth' 
			FROM InventoryLine L
			JOIN Inventory S ON L.StockNo = S.StockNo
			WHERE MONTH( S.Date ) ='$month3'
			AND L.StockNo like '%PC%'
			GROUP BY L.ProductCode";
			mysql_query($sql,$conn) or die(mysql_error());
		
		$sql = "SELECT V.ProductCode, P.ProductName, V.TotalQuantityIninMonth, V.TotalQuantityOutinMonth
FROM V1 V JOIN Product P ON V.ProductCode=P.ProductCode";
		$result = mysql_query($sql,$conn);	
		while($data=mysql_fetch_assoc($result))
				$listData[]=$data;
				
    	$sql="SELECT SUM(V.TotalQuantityIninMonth),SUM(V.TotalQuantityOutinMonth) FROM V1 V";
		$sumResult = mysql_query($sql,$conn);
    	list($sumTotalIn,$sumTotalOut) = mysql_fetch_row($sumResult);
		
		mysql_free_result($result);
    	mysql_free_result($sumResult);


$pdf=new FPDF();
//Column titles InvoiceDate,TotalAmount,VAT,AmountDue
$header=array('Month','Poduct Code','Product Name','Quantity In');
$pdf->AddPage();
$pdf->SetFont('Arial','B',20);
//report Header
//$pdf->SetTextColor(255,0,0);
$pdf->Cell(0,7,'Total of each Product in Month',0,0,'C');
//New line
$pdf->Ln();
$pdf->Ln(15);
$pdf->SetFont('Arial','B',15);
$pdf->Cell(0,7,'Month :',0,0,'L');
$pdf->Cell(0,7,$month3,0,0,'C');

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
//$pdf->Cell($w[3],4,$row['TotalQuantityinMonth'],L,0,'R',$fill);
$pdf->Cell($w[3],5,$row['TotalQuantityIninMonth'],LR,0,'R',$fill);


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
$pdf->Cell($w[3],4,number_format($sumTotalIn,0),LR,0,'R',$fill);

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