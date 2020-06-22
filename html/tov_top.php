<?php
include ("config.php");
echo "<script language='JavaScript'>";
	echo "
	function PutBarCode(BarCode)
	{
		window.opener.oborot.Shtrih.value=BarCode;
	}
	";
echo "</script>";

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
						echo "<table align='center' width=80%>";

						//Таблиця ходовим товаром
							echo "<TR>";
								echo "<TD align='center'>";
									$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
									$TableData = $DataModule->GetBestPreparats($_REQUEST["Count"],$_REQUEST["Date1"],$_REQUEST["Date2"]);
									echo "<font class='Midle'>".$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->FullName."</font><BR>";
									echo "<font class='Midle'>".$FormPreparatItem6." ".$From." ".$_REQUEST["Date1"]." ".$To." ".$_REQUEST["Date2"]."</font>";
									
									$Table = new Table();
									$Table->Width=100;
									echo $Table->TableBegin(6);
									
									$Row = array($FormPreparatItem4, $FormPreparatItem1, $FormTovarItem14, $FormTovarDefecturaItem8, $FormImportHeader6, $FormImportHeader7);
									$Table->ChangeAlign(3,'Right');
									$Table->ChangeAlign(4,'Right');
									$Table->ChangeAlign(5,'Right');
									$Table->ChangeAlign(6,'Right');

									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
									
									while ($rowresult=ibase_fetch_assoc($TableData))
									{
										$BarCode="<a class='SimpleColor' href='javascript:PutBarCode(".$rowresult['SBARCODE'].");'>".$rowresult['SBARCODE']."</a>";
										$TableRow = array($BarCode, $rowresult['SNOMEN_NAME'], number_format($rowresult['COUNTTOV'],2,",",""), number_format($rowresult['QUANT'],2,",",""), number_format($rowresult['SUMGRN'],2,",",""), number_format($rowresult['NTAXSUM'],2,",",""));
										echo $Table->TableRow($TableRow);
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