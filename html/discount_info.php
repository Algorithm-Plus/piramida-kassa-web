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

echo "<title>".$FormDiscountTitle2."</title>";
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
									$TotalCheck = 0;
                  $TotalCash = 0;
                  $TotalDiscount = 0;
									if ($_REQUEST["Type"]==1)
									{
										$TableData = $DataModule->GetCheckListForDiscount($_REQUEST["Date1"], $_REQUEST["Date2"], $_REQUEST["Shtrih"],$_SESSION["AptekaList"]);                    
										$Table = new Table();
										$Row = array($FormCheckHeader1, $FormCheckHeader2, $FormCheckHeader3, $FormCheckHeader4, $FormCheckHeader5, $FormCheckHeader6, $FormCheckHeader7, $FormDiscountHeader8);
										$Table->AddRow($Row);
										$Table->LastRowIsTableHeader();//Останній доданий рядок був заголовком в таблиці
										for ($i=1; $i<=count($TableData); $i++)
										{
											$TableRow = $TableData[$i];
											//$Link = "<a class='SimpleColor' target='_blank' href='get_check.php?id=". $TableRow[2]."'>". $TableRow[1]."</a>";
											$Link =Date("d.m.Y H:i:s",strtotime($TableRow[1]));
											$Row = array($Link, $TableRow[2], $TableRow[3], number_format($TableRow[5],2,",",""), number_format($TableRow[6],2,",",""), number_format($TableRow[7],2,",",""), number_format($TableRow[8],2,",",""), $TableRow[9]);
											$Table->AddRow($Row);
                      $TotalCheck = $TotalCheck+$TableRow[5];
                      $TotalCash = $TotalCash+$TableRow[7];
                      $TotalDiscount = $TotalDiscount+$TableRow[8];
										}
                    $Row = array('', '', '', number_format($TotalCheck,2,",",""), '', number_format($TotalCash,2,",",""), number_format($TotalDiscount,2,",",""), '');
                    $Table->AddRow($Row);
										$Table->LastRowIsTableHeader();//Останній доданий рядок був заголовком в таблиці
                    
										$Table->ChangeAlign(4,'Right'); //Колонки з сумами по правому краю
										$Table->ChangeAlign(5,'Right');
										$Table->ChangeAlign(6,'Right');
										$Table->ChangeAlign(7,'Right');
										$Table->ChangeAlign(8,'Right');
	
										$TableInfo = new TableInfo($FormCheckItem2." ".$_REQUEST["Date1"]." - ".$_REQUEST["Date2"]); //Створюю і вивожу інформативний блок з таблицею $Table->Show()
										$TableInfo->ChangeBorder(2);
										$TableInfo->AddBody($Table->Show());
										echo $TableInfo->Show();
                    
									}
									if ($_REQUEST["Type"]==2)
									{
										$TableData = $DataModule->GetTovarForDiscount($_REQUEST["Date1"], $_REQUEST["Date2"], $_REQUEST["Shtrih"],$_SESSION["AptekaList"]);
										$Table = new Table();
										$Row = array($FormDiscountHeader9, $FormDiscountHeader10, $FormDiscountHeader12, $FormDiscountHeader13, $FormDiscountHeader14, $FormDiscountHeader15, $FormDiscountHeader16, $FormDiscountHeader17, $FormReportHeader1_12, $FormPreparatItem10, $FormTovarProdagiHeader7, $FormDiscountHeader18, $FormDiscountHeader19);
										$Table->AddRow($Row);
										$Table->LastRowIsTableHeader();//Останній доданий рядок був заголовком в таблиці
										$Sum1 = 0;
										$Sum2 = 0;
										$Sum3 = 0;
										for ($i=1; $i<=count($TableData); $i++)
										{
											$TableRow = $TableData[$i];
											$Link =Date("d.m.Y",strtotime($TableRow[1]));
											$Row = array($Link, $TableRow[2], $TableRow[4], $TableRow[5],
																	 number_format($TableRow[6],2,",",""),
																	 number_format($TableRow[7],2,",",""),
																	 number_format($TableRow[8],2,",",""),
																	 number_format($TableRow[9],2,",",""),
																	 number_format($TableRow[5]*$TableRow[6],2,",",""),
																	 number_format($TableRow[12],2,",",""),
																	 number_format($TableRow[12]*$TableRow[5],2,",",""),
																	 $TableRow[10],
																	 $TableRow[11]);
											$Table->AddRow($Row);
											$Sum1 = $Sum1+$TableRow[9];
											$Sum2 = $Sum2 + ($TableRow[12]*$TableRow[5]);
											$Sum3 = $Sum3 + ($TableRow[5]*$TableRow[6]);
										}
										$Row = array("", "", "", "", "", "", "", number_format($Sum1,2,",",""), number_format($Sum3,2,",",""), "", number_format($Sum2,2,",",""), "", "");
										$Table->AddRow($Row);
										$Table->LastRowIsTableHeader();
										$Table->ChangeAlign(5,'Right');
										$Table->ChangeAlign(6,'Right');
										$Table->ChangeAlign(7,'Right');
										$Table->ChangeAlign(8,'Right');
										$Table->ChangeAlign(9,'Right');
										$Table->ChangeAlign(10,'Right');
										$Table->ChangeAlign(11,'Right');
	
										$TableInfo = new TableInfo($FormCheckItem2." ".$_REQUEST["Date1"]." - ".$_REQUEST["Date2"]); //Створюю і вивожу інформативний блок з таблицею $Table->Show()
										$TableInfo->ChangeBorder(2);
										$TableInfo->AddBody($Table->Show());
										echo $TableInfo->Show();
									}
									
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