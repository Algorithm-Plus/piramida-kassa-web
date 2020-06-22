<?php
include ("page_top.php");
						echo "<BR>";
echo "<p align=center>";
$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
$TableData = $DataModule->GetSumFromDiscountAllApteks('01.01.2001',date("d.m.Y"),1,$_SESSION["AptekaList"], $_REQUEST["Shtrih"]);
echo "<font class='Midle'>".$FormDiscountTitle1."</font><BR>";							
$Table = new Table();
echo $Table->TableBegin(7);
$Row = array($FormDiscountHeader1, $FormDiscountHeader2, $FormDiscountHeader6, $FormDiscountHeader7, $FormDiscountHeader3, $FormDiscountHeader4, $FormDiscountHeader5);
$Table->ChangeAlign(3,'Right');
$Table->ChangeAlign(4,'Right');
$Table->ChangeAlign(5,'Right');
$Table->ChangeAlign(6,'Right');
$Table->NextRowIsHeader(true);
echo $Table->TableRow($Row);
$Table->NextRowIsHeader(false);
//print_r($TableData);
for ($i=1; $i<=count($TableData); $i++)
{
	$TableRow = $TableData[$i];
	//$BarCode="<a class='SimpleColor' href='javascript:PutBarCode(".$TableRow[1].");'>".$TableRow[1]."</a>";
	$BarCode=$TableRow[1];
	$Row = array($BarCode, $TableRow[2], $TableRow[7], $TableRow[8], $TableRow[3], $TableRow[4],$TableRow[5]);
	echo $Table->TableRow($Row);
}			
echo $Table->TableEnd();
echo "</p>";
include("page_bottom.php");
?>