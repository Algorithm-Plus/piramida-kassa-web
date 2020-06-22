<?php
// Показуємо обороти з сторінки залишків по всім точкам

include ("config.php");

//Формування звіту по аптеці
function ShowReportForApteka($Apteka, $Date1, $Date2)
{
	include("language/".$_SESSION["Language"].".php");
	echo "<p align='center'>";
	echo "<table width='100%'>";
		echo "<tr>";
			echo "<td align='center' class='TabStyleDark'>";
				echo "<font class='Big'>".$Apteka->FullName."</font>";
			echo "</td>";
		echo "</tr>";
	echo "</table>";
	echo "<BR>";
	echo "<font class='Midle'>".$FormReportItem10."</font>";
	$DataModule = new DataModule($Apteka);
	//1- к-ть >0
	$Table = new Table();
	echo $Table->TableBegin(4);
	$Row = array($FormReportHeader2_1, $FormImportHeader3, $FormImportHeader2, $FormTovarOborotItem12);
	$Table->NextRowIsHeader(true);
	echo $Table->TableRow($Row);
	$Table->NextRowIsHeader(false);
	
	// не продавався місяць
	$TempDate = date("d").".".date("m", mktime(0, 0, 0, date("m")-1  , date("d"), date("Y"))).".".date("Y");
	$Table->NextRowIsHeader(true);
	$Row = array($FormReportHeader2_2." (".$TempDate." - ".$Date2.")", "", "", "");
	echo $Table->TableRow($Row);
	$Table->NextRowIsHeader(false);
	$TableData1 = $DataModule->GetAllTovarWithOborotWithQuatity($TempDate, $Date2, 1);
	for ($i=1; $i<count($TableData1); $i++)
	{
		$TableRow = $TableData1[$i];
		$Row = array("", $TableRow[1], $TableRow[2], $TableRow[3]);
		echo $Table->TableRow($Row);
	}

	// не продавався 3 міс.
	$TempDate = date("d").".".date("m", mktime(0, 0, 0, date("m")-3  , date("d"), date("Y"))).".".date("Y");
	$Table->NextRowIsHeader(true);
	$Row = array($FormReportHeader2_3." (".$TempDate." - ".$Date2.")", "", "", "");
	echo $Table->TableRow($Row);
	$Table->NextRowIsHeader(false);
	$TableData3 = $DataModule->GetAllTovarWithOborotWithQuatity($TempDate, $Date2, 1);
	for ($i=1; $i<count($TableData3); $i++)
	{
		$TableRow = $TableData3[$i];
		$Row = array("", $TableRow[1], $TableRow[2], $TableRow[3]);
		echo $Table->TableRow($Row);
	}

	// не продавався 6 міс.
	$TempDate = date("d").".".date("m.Y", mktime(0, 0, 0, date("m")-6 , date("d"), date("Y")));
	$Table->NextRowIsHeader(true);
	$Row = array($FormReportHeader2_4." (".$TempDate." - ".$Date2.")", "", "", "");
	echo $Table->TableRow($Row);
	$Table->NextRowIsHeader(false);
	$TableData6 = $DataModule->GetAllTovarWithOborotWithQuatity($TempDate, $Date2, 1);
	for ($i=1; $i<count($TableData6); $i++)
	{
		$TableRow = $TableData6[$i];
		$Row = array("", $TableRow[1], $TableRow[2], $TableRow[3]);
		echo $Table->TableRow($Row);
	}
	/*	
	// не продавався 12 міс.
	$TempDate = date("d").".".date("m.Y", mktime(0, 0, 0, date("m"), date("d"), date("Y")-1));
	$Table->NextRowIsHeader(true);
	$Row = array($FormReportHeader2_5." (".$TempDate." - ".$Date2.")", "", "", "");
	echo $Table->TableRow($Row);
	$Table->NextRowIsHeader(false);
	$TableData12 = $DataModule->GetAllTovarWithOborotWithQuatity($TempDate, $Date2, 1);
	for ($i=1; $i<count($TableData12); $i++)
	{
		$TableRow = $TableData12[$i];
		$Row = array("", $TableRow[1], $TableRow[2], $TableRow[3]);
		echo $Table->TableRow($Row);
	}
	*/
	echo $Table->TableEnd();
	
// ===========================================Деталі по приході
	
	echo "<BR>";
	echo "<font class='Midle'>".$FormReportItem11."</font>";
	echo "<BR>";
	

	echo "<font class='Midle'>".$FormReportHeader2_2."</font>";
	// не продавався місяць
	$TempDate = date("d").".".date("m", mktime(0, 0, 0, date("m")-1  , date("d"), date("Y"))).".".date("Y");
	$Table = new Table();
	echo $Table->TableBegin(3);
	$Table->NextRowIsHeader(true);
	$Row = array($FormReportHeader1_6, $FormImportHeader3, $FormTovarDefecturaItem7);
	echo $Table->TableRow($Row);
	$Table->NextRowIsHeader(false);
	for ($i=1; $i<count($TableData1); $i++)
	{
		if ($TableData1[$i][5]>0) //Є прихід
		{
			$Table->NextRowIsHeader(true);
			$Row = array($TableData1[$i][2], $TableData1[$i][1], "");
			echo $Table->TableRow($Row);
			$Table->NextRowIsHeader(false);
				$TableData = $DataModule->GetOborot($TableData1[$i][1],$TempDate,$Date2,"");
				$ShowRow = false;
				for ($j=1; $j<count($TableData); $j++)
				{
					if ($TableData[$j][3]=="ПРИХІД ТОВАРУ:") {$ShowRow = true; continue;}
					if ($TableData[$j][3]=="ВСЬОГО ПО ПРИХОДУ:") {$ShowRow = false;}
					if ($ShowRow)
					{
						$Row = array("", "", $TableData[$j][1].", ".$TableData[$j][2].", ".$TableData[$j][6]);
						echo $Table->TableRow($Row);
					}
				}
		}
	}
	echo $Table->TableEnd();
	
	echo "<BR>";
	echo "<font class='Midle'>".$FormReportHeader2_3."</font>";
	// не продавався 3 міс.	
	$TempDate = date("d").".".date("m", mktime(0, 0, 0, date("m")-3  , date("d"), date("Y"))).".".date("Y");
	$Table = new Table();
	echo $Table->TableBegin(3);
	$Table->NextRowIsHeader(true);
	$Row = array($FormReportHeader1_6, $FormImportHeader3, $FormTovarDefecturaItem7);
	echo $Table->TableRow($Row);
	$Table->NextRowIsHeader(false);
	for ($i=1; $i<count($TableData3); $i++)
	{
		if ($TableData3[$i][5]>0) //Є прихід
		{
			$Table->NextRowIsHeader(true);
			$Row = array($TableData3[$i][2], $TableData3[$i][1], "");
			echo $Table->TableRow($Row);
			$Table->NextRowIsHeader(false);
				$TableData = $DataModule->GetOborot($TableData3[$i][1],$TempDate,$Date2, "");
				$ShowRow = false;
				for ($j=1; $j<count($TableData); $j++)
				{
					if ($TableData[$j][3]=="ПРИХІД ТОВАРУ:") {$ShowRow = true; continue;}
					if ($TableData[$j][3]=="ВСЬОГО ПО ПРИХОДУ:") {$ShowRow = false;}
					if ($ShowRow)
					{
						$Row = array("", "", $TableData[$j][1].", ".$TableData[$j][2].", ".$TableData[$j][6]);
						echo $Table->TableRow($Row);
					}
				}
		}
	}	
	echo $Table->TableEnd();
	
	echo "<BR>";
	echo "<font class='Midle'>".$FormReportHeader2_4."</font>";
	// не продавався 6 міс.
	$TempDate = date("d").".".date("m.Y", mktime(0, 0, 0, date("m")-6 , date("d"), date("Y")));
	$Table = new Table();
	echo $Table->TableBegin(3);
	$Table->NextRowIsHeader(true);
	$Row = array($FormReportHeader1_6, $FormImportHeader3, $FormTovarDefecturaItem7);
	echo $Table->TableRow($Row);
	$Table->NextRowIsHeader(false);
	for ($i=1; $i<count($TableData6); $i++)
	{
		if ($TableData6[$i][5]>0) //Є прихід
		{
			$Table->NextRowIsHeader(true);
			$Row = array($TableData6[$i][2], $TableData6[$i][1], "");
			echo $Table->TableRow($Row);
			$Table->NextRowIsHeader(false);
				$TableData = $DataModule->GetOborot($TableData6[$i][1],$TempDate,$Date2,"");
				$ShowRow = false;
				for ($j=1; $j<count($TableData); $j++)
				{
					if ($TableData[$j][3]=="ПРИХІД ТОВАРУ:") {$ShowRow = true; continue;}
					if ($TableData[$j][3]=="ВСЬОГО ПО ПРИХОДУ:") {$ShowRow = false;}
					if ($ShowRow)
					{
						$Row = array("", "", $TableData[$j][1].", ".$TableData[$j][2].", ".$TableData[$j][6]);
						echo $Table->TableRow($Row);
					}
				}
		}
	}	
	echo $Table->TableEnd();
	
	/*
	echo "<BR>";
	echo "<font class='Midle'>".$FormReportHeader2_5."</font>";
	// не продавався 12 міс.
	$TempDate = date("d").".".date("m.Y", mktime(0, 0, 0, date("m"), date("d"), date("Y")-1));
	$Table = new Table();
	echo $Table->TableBegin(3);
	$Table->NextRowIsHeader(true);
	$Row = array($FormReportHeader1_6, $FormImportHeader3, $FormTovarDefecturaItem7);
	echo $Table->TableRow($Row);
	$Table->NextRowIsHeader(false);
	for ($i=1; $i<count($TableData12); $i++)
	{
		if ($TableData12[$i][5]>0) //Є прихід
		{
			$Table->NextRowIsHeader(true);
			$Row = array($TableData12[$i][2], $TableData12[$i][1], "");
			echo $Table->TableRow($Row);
			$Table->NextRowIsHeader(false);
				$TableData = $DataModule->GetOborot($TableData12[$i][1],$TempDate,$Date2);
				$ShowRow = false;
				for ($j=1; $j<count($TableData); $j++)
				{
					if ($TableData[$j][3]=="ПРИХІД ТОВАРУ:") {$ShowRow = true; continue;}
					if ($TableData[$j][3]=="ВСЬОГО ПО ПРИХОДУ:") {$ShowRow = false;}
					if ($ShowRow)
					{
						$Row = array("", "", $TableData[$j][1].", ".$TableData[$j][2].", ".$TableData[$j][6]);
						echo $Table->TableRow($Row);
					}
				}
		}
	}	
	echo $Table->TableEnd();
	*/
	/**/
	echo "</p>";
	
}

echo "<BODY class='Default'>";
echo "<LINK REL='stylesheet' href='template/".$_SESSION["Template"]."/style.css' type='text/css'>";
echo "<table class='NoMargin' border=0>";
				echo "<TR>";
					echo "<TD width=6px height=6px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/c_lt.jpg'>";	
					echo "</TD>";
					echo "<TD style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-x;' background='template/".$_SESSION["Template"]."/img/l_t.jpg'>";
					echo "</TD>";
					echo "<TD width=6px height=6px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/c_rt.jpg'>";	
					echo "</TD>";
				echo "</TR>";
				echo "<TR>";
					echo "<TD width='6px' style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-y;' background='template/".$_SESSION["Template"]."/img/l_l.jpg'>";
					echo "</TD>";
					echo "<TD width=".$WidthPage."px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-yx;' background='template/".$_SESSION["Template"]."/img/bkground1.jpg'>";
//---------------------------Дані на сторінці
						echo "<BR>";
						echo "<p align='center'>";
						echo "<font class='Midle'>".$FormReportAlt6."</font>";
						echo "<BR>";
						echo "<font class='Midle'>".$From." ".$_REQUEST["Date1"]." ".$To." ".$_REQUEST["Date2"]."</font>";
						echo "</p>";
						if ($_REQUEST["type"]==1) //По всіх
							{
								for ($i=1; $i<=Count($_SESSION["AptekaList"]); $i++)
								{ 
									if ($_SESSION["AptekaList"][$i]->Active==1)
									{
										ShowReportForApteka($_SESSION["AptekaList"][$i], $_REQUEST["Date1"], $_REQUEST["Date2"]);									
									}
								}
							}
//---------------------------
					echo "</TD>";
					echo "<TD width='6px' style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-y;' background='template/".$_SESSION["Template"]."/img/l_r.jpg'>";
					echo "</TD>";
				echo "</TR>";

				echo "<TR>";
					echo "<TD width=6px height=6px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/c_lb.jpg'>";	
					echo "</TD>";
					echo "<TD height=6px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: repeat-x;' background='template/".$_SESSION["Template"]."/img/l_b.jpg'>";
					echo "</TD>";
					echo "<TD width=6px height=6px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/c_rb.jpg'>";	
					echo "</TD>";
				echo "</TR>";

echo "</table>";
echo "</BODY>";
?>