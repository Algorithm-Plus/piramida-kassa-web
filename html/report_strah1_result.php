<?php
include ("config.php");

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
						echo "<font class='Midle'>".$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->FullName."</font>";
						echo "<BR>";
						echo "<p align='center'";
							echo "<font class='Midle'>";
								$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
								$InsurerName = $DataModule->GetInsurerByID($_REQUEST["Insurer"]);
								echo $InsurerName[1][3];
								echo "<BR>";
								echo $From." ".$_REQUEST["Date1"]." ".$To." ".$_REQUEST["Date2"];
							echo "</font>";
							echo "<BR>";
							$TableData = $DataModule->ReportStrah1($_REQUEST["Date1"],$_REQUEST["Date2"],$_REQUEST["Insurer"]);
							//$_SESSION['price'] = $TableData;
							$Table = new Table();
							echo $Table->TableBegin(10);
							$Table->ChangeAlign(7,'Right');
							$Table->ChangeAlign(8,'Right');
							$Table->ChangeAlign(9,'Right');
							$Table->ChangeAlign(10,'Right');
							$Row = array($FormReportHeader1_1, $FormReportHeader1_2, $FormReportHeader1_3, $FormReportHeader1_4, $FormReportHeader1_5, $FormReportHeader1_6, $FormReportHeader1_7, $FormReportHeader1_8, $FormReportHeader1_9, $FormReportHeader1_9."2");
							$Table->NextRowIsHeader(true);
							echo $Table->TableRow($Row);
							$Table->NextRowIsHeader(false);
							$TotalSum = 0;
							for ($i=1; $i<count($TableData); $i++)
							{
								$TableRow = $TableData[$i];
								$Row = array($TableRow[1], $TableRow[2], $TableRow[3], $TableRow[4], $TableRow[5], $TableRow[6], $TableRow[7], $TableRow[8], $TableRow[9], $TableRow[13]);
								echo $Table->TableRow($Row);
								$TotalSum = $TotalSum + $TableRow[9];
								//echo $TableRow[10];
								if ($TableRow[10]<>0)
								{
									$Row = array("","","","","",$FormReportHeader1_10,"","",$TableRow[10],"");
									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
								}
								if ($TableRow[11]<>0)
								{
									$Row = array("","","","","",$FormReportHeader1_11,"","",$TableRow[11],"");
									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
								}
							}
							
							$Row = array("","","","","","","","",number_format($TotalSum,2,",",""),number_format($TotalSum*$GLOBALS["glReportStrahPriceKoef"],2,",",""));
							$Table->NextRowIsHeader(true);
							echo $Table->TableRow($Row);
							
							echo $Table->TableEnd();
							
							echo "<BR><BR>";
							echo "<form name='strah' id='strah' METHOD=post ACTION='tov_def_savedbf.php?a=one' target='_blank'>";
								echo "<INPUT type=hidden name='Date1' value=".$_REQUEST["Date1"].">";
								echo "<INPUT type=hidden name='Date2' value=".$_REQUEST["Date2"].">";
								echo "<INPUT type=hidden name='Insurer' value=".$_REQUEST["Insurer"].">";
								echo "<INPUT class='ButtonPasive' TYPE='Submit'  value='".$lnSaveToXML."'><BR>";
							echo "</form>";
							echo "<form name='strah' id='strah' METHOD=post ACTION='tov_def_savedbf.php?a=all&type=xml' target='_blank'>";
								echo "<INPUT type=hidden name='Date1' value=".$_REQUEST["Date1"].">";
								echo "<INPUT type=hidden name='Date2' value=".$_REQUEST["Date2"].">";
								echo "<INPUT type=hidden name='Insurer' value=".$_REQUEST["Insurer"].">";
								echo "<INPUT class='ButtonPasive' TYPE='Submit'  value='".$lnSaveToXML." ".$FormTovarProdagi4."'><BR>";
							echo "</form>";
						echo "</p>";
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