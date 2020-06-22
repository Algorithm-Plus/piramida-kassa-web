<?php
include ("config.php");
echo "<script language='JavaScript'>";
	echo "
	function PutBarCode(BarCode)
	{
		window.opener.oborot.Shtrih.value='+'+BarCode;
	}
	";
echo "</script>";

echo "<title>".$FormDiscountTitle1."</title>";
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
						echo "<table align='center'>";

						//Таблиця 
							echo "<TR>";
								echo "<TD align='center'>";
									$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
									if (isset($_REQUEST["AllApteks"]))
									{
										$TableData = $DataModule->GetSumFromDiscountAllApteks($_REQUEST["Date1"],$_REQUEST["Date2"],$_REQUEST["Type"],$_SESSION["AptekaList"]);
										echo "<font class='Midle'>".$FormTovarProdagi4."</font><BR>";
									}
									else
									{									
										$TableData = $DataModule->GetSumFromDiscount($_REQUEST["Date1"],$_REQUEST["Date2"],$_REQUEST["Type"]);
										echo "<font class='Midle'>".$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->FullName."</font><BR>";
									}
									echo "<font class='Midle'>".$FormDiscountTitle1."</font><BR>";
									echo "<font class='Midle'>".$From." ".$_REQUEST["Date1"]." ".$To." ".$_REQUEST["Date2"]."</font>";
									
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
										$BarCode="<a class='SimpleColor' href='javascript:PutBarCode(".$TableRow[1].");'>".$TableRow[1]."</a>";
										$Row = array($BarCode, $TableRow[2], $TableRow[7], $TableRow[8], $TableRow[3], $TableRow[4],$TableRow[5]);
										echo $Table->TableRow($Row);
									}
									
									echo $Table->TableEnd();
								echo "</TD>";

							echo "</TR>";
						echo "</table>";
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