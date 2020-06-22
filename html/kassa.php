<?php
include ("config.php");
$_SESSION["CurrentSection"]=2;
echo "<script language='JavaScript'>";
	echo "parent.frames['frametop'].location.reload();";
echo "</script>";

echo "<script src='calendar.js' type='text/javascript' language='javascript'></script>";

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
						//echo "kasa ".$_SESSION["CurrentApteka"];
						echo "<BR>";
						echo "<table>";

						//Загальна інформація
							echo "<TR>";
								echo "<TD valign='top' colspan=2>";
									$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
									$Sum1 = $DataModule->GetTotalReceiptsSum(date("d.m.Y")." 00:00:00", date("d.m.Y")." 23:59:59");
                  $Sum2 = $DataModule->GetTotalReceiptsSum((date("d")-1).".".date("m").".".date("Y")." ".$glShiftBegin, date("d.m.Y")." ".$glShiftEnd);
                  $Sum3 = $DataModule->GetTotalReceiptsSum(date("d.m.Y")." ".$glShiftBegin, date("d.m.Y")." 23:59:59");

									$Table = new Table();
									$Row = array(' ', date("d.m.Y"), $TableShift1, $TableShift2);
									$Table->AddRow($Row);
									//$Table->ChangeLastRowDark();
									$Table->LastRowIsTableHeader();//Останній доданий рядок був заголовком в таблиці
									//$Row = array($FormKassaItem1, $Sum1[3], $Sum2[3], $Sum3[3]);
									//$Table->AddRow($Row);
									
									//$Row = array($FormKassaTitle15, $Sum1[11], $Sum2[11], $Sum3[11]);
									//$Table->AddRow($Row);
                  
									$Row = array($FormKassaItem3, $Sum1[5], $Sum2[5], $Sum3[5]);
									$Table->AddRow($Row);
                  $Row = array($FormKassaItem11, $Sum1[12]+($Sum1[5]-$Sum1[8]), $Sum2[12]+($Sum2[5]-$Sum2[8]), $Sum3[12]+($Sum3[5]-$Sum3[8]));
									$Table->AddRow($Row);									             
                  $Row = array($FormKassaItem7, $Sum1[8], $Sum2[8], $Sum3[8]);//фіскальна сума
									$Table->AddRow($Row);
                  $Table->ChangeLastRowDark(); //Підсвітити фіскалку
                  $Row = array($FormKassaItem10, $Sum1[12], $Sum2[12], $Sum3[12]);//готівкою
									$Table->AddRow($Row);
                  $Row = array($FormKassaItem5, $Sum1[7], $Sum2[7], $Sum3[7]);//карткою
									$Table->AddRow($Row);
									$Row = array($FormKassaItem8, $Sum1[9], $Sum2[9], $Sum3[9]);
									$Table->AddRow($Row);
                  $Table->ChangeLastRowDark();
                  /*
                  $Row = array("", "", "", "");
									$Table->AddRow($Row);
									$Table->ChangeLastRowDark();
                  */
                  $Row = array($FormKassaItem4, $Sum1[6], $Sum2[6], $Sum3[6]);
									$Table->AddRow($Row);
                  $Row = array($FormKassaItem2, $Sum1[4], $Sum2[4], $Sum3[4]);
									$Table->AddRow($Row);
                   $Row = array($FormKassaItem9, $Sum1[10], $Sum2[10], $Sum3[10]);
									$Table->AddRow($Row);
                  	
									$Table->ChangeAlign(2,'Right'); //Колонки з сумами по правому краю
                  $Table->ChangeAlign(3,'Right');
                  $Table->ChangeAlign(4,'Right');

									$TableInfo = new TableInfo($FormKassaTitle1." ".$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->FullName); //Створюю і вивожу інформативний блок з таблицею $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Table->Show());
									echo $TableInfo->Show();
								echo "</TD>";
						//Статистика попередніх періодів
								echo "<TD valign='top' colspan=2>";
                /*
									//Дані на сьогодні
									//$DataModule = new DataModule($AptekaList[$_SESSION["CurrentApteka"]]);
									$Sum1 = $DataModule->GetTotalReceiptsSum(date("d.m.Y")." 00:00:00", date("d.m.Y")." 23:59:59");
									//$Sum1 = $DataModule->GetTotalReceiptsSum("08.11.2010", "08.11.2010");
									//Дані минулого місяця
									//$DataModule = new DataModule($AptekaList[$_SESSION["CurrentApteka"]]);
									$Sum2 = $DataModule->GetTotalReceiptsSum(date("d").".".(date("m")-1).".".date("Y")." 00:00:00", date("d").".".(date("m")-1).".".date("Y")." 23:59:59");
									//Дані минулого року
									//$DataModule = new DataModule($AptekaList[$_SESSION["CurrentApteka"]]);
									$Sum3 = $DataModule->GetTotalReceiptsSum(date("d").".".date("m").".".(date("Y")-1)." 00:00:00", date("d").".".date("m").".".(date("Y")-1)." 23:59:59");						
									//шукаю максимальне значення для маштабування
									$max = $Sum1[5];
									if ($max<$Sum2[5]) {$max=$Sum2[5];}
									if ($max<$Sum3[5]) {$max=$Sum3[5];}

									$Table = new Table();
									$Row = array(date("d.m.Y"),$FormKassaItem1."<BR>".$FormKassaItem4, $Sum1[5]."<BR>".$Sum1[6], "<img width=".$Sum1[5]*$ZoomGraph/$max."px height=10px src='template/".$_SESSION["Template"]."/img/grad1.jpg'><BR><img width=".$Sum1[6]*$ZoomGraph/$max."px height=10px src='template/".$_SESSION["Template"]."/img/grad2.jpg'>");
									$Table->AddRow($Row);
									$Row = array(date("d").".".(date("m")-1).".".date("Y"),$FormKassaItem1."<BR>".$FormKassaItem4, $Sum2[5]."<BR>".$Sum2[6], "<img width=".$Sum2[5]*$ZoomGraph/$max."px height=10px src='template/".$_SESSION["Template"]."/img/grad1.jpg'><BR><img width=".$Sum2[6]*$ZoomGraph/$max."px height=10px src='template/".$_SESSION["Template"]."/img/grad2.jpg'>");
									$Table->AddRow($Row);
									$Row = array(date("d").".".date("m").".".(date("Y")-1),$FormKassaItem1."<BR>".$FormKassaItem4, $Sum3[5]."<BR>".$Sum3[6], "<img width=".$Sum3[5]*$ZoomGraph/$max."px height=10px src='template/".$_SESSION["Template"]."/img/grad1.jpg'><BR><img width=".$Sum3[6]*$ZoomGraph/$max."px height=10px src='template/".$_SESSION["Template"]."/img/grad2.jpg'>");
									$Table->AddRow($Row);
									$Table->ChangeAlign(3,'Right');

									$TableInfo = new TableInfo($FormKassaTitle11); //Створюю і вивожу інформативний блок з таблицею 
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Table->Show());
									echo $TableInfo->Show();
                */
								echo "</TD>";
							echo "</TR>";
							echo "<TR>";
								echo "<TD valign='top'>";
									$Form = "
									<form name='kassa' method=post target='_blank' action='kassadata.php'>
                    <table>
                    <tr>
                    <td>
										<font> ".$From." </font></td><td><input type=text class='DateTime' name='Date1' value='01.".date("m.Y")." 00:00:00'><font></td></tr>
											<!--a href='javascript:openCalendar();'><img border=0 valign='center' align='absmiddle' src='template/".$_SESSION["Template"]."/img/b_calendar.png'></a--> 
										<tr><td>".$To."</font></td><td><input type=text class='DateTime' name='Date2' value='".date("d.m.Y")." 23:59:59'></td></tr>
                    </table>
										<BR>
										<input type=Submit class='ButtonPasive' value='".$FormKassaItem6."'>
									</form>";

									$TableInfo = new TableInfo($FormKassaTitle12);
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Form);
									echo $TableInfo->Show();
								echo "</TD>";
								//Зозбивка по продавцям
								echo "<TD valign='top'>";
									/*
									$TableSum = $DataModule-> GetSumFromSeller(date("d.m.Y"), date("d.m.Y"));

									$Table = new Table();
									$Row = array($FormKassaTitle3, $FormKassaTitle4, $TopFrameItem4, $FormKassaTitle14);
									$Table->AddRow($Row);
									$Table->LastRowIsTableHeader();//Останній доданий рядок був заголовком в таблиці
									
									for ($i=1; $i<=count($TableSum); $i++)
									{
										$TableRow = $TableSum[$i];
										$Row = array($TableRow[1], $TableRow[2], $TableRow[3], number_format($TableRow[2]/$TableRow[3],2,",",""));
										$Table->AddRow($Row);
									}

									$Table->ChangeAlign(2,'Right'); //Колонки з сумами по правому краю
                                                                        $Table->ChangeAlign(4,'Right'); 

									$TableInfo = new TableInfo($FormKassaTitle2); //Створюю і вивожу інформативний блок з таблицею $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Table->Show());
									echo $TableInfo->Show();
                  */
								echo "</TD>";
								
								echo "<TD valign='top'>";

									//Розбивка по знижкам
									/*
									$TableSum = $DataModule-> GetSumFromDiscont(date("d.m.Y"), date("d.m.Y"));

									$Table = new Table();
									$Row = array($FormKassaTitle7, $FormKassaTitle13, $FormKassaTitle8);
									$Table->AddRow($Row);
									$Table->LastRowIsTableHeader();//Останній доданий рядок був заголовком в таблиці
									
									for ($i=1; $i<=count($TableSum); $i++)
									{
										$TableRow = $TableSum[$i];
										$Row = array($TableRow[1], $TableRow[3], $TableRow[2]);
										$Table->AddRow($Row);
									}
									$Table->ChangeAlign(1,'Right');
									$Table->ChangeAlign(2,'Right'); //Колонки з сумами по правому краю

									$TableInfo = new TableInfo($FormKassaTitle9); //Створюю і вивожу інформативний блок з таблицею $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Table->Show());
									echo $TableInfo->Show();
                  */
								echo "</TD>";

								//Розбивка по лікарям
								echo "<TD valign='top'>";
                /*
									//$DataModule = new DataModule($AptekaList[$_SESSION["CurrentApteka"]]);
									$TableSum = $DataModule-> GetSumFromDoctor(date("d.m.Y"), date("d.m.Y"));

									$Table = new Table();
									$Row = array($FormKassaTitle5, $FormKassaTitle6);
									$Table->AddRow($Row);
									$Table->LastRowIsTableHeader();//Останній доданий рядок був заголовком в таблиці
									
									for ($i=1; $i<=count($TableSum); $i++)
									{
										$TableRow = $TableSum[$i];
										$Row = array($TableRow[1], $TableRow[2]);
										$Table->AddRow($Row);
									}

									$Table->ChangeAlign(2,'Right'); //Колонки з сумами по правому краю

									$TableInfo = new TableInfo($FormKassaTitle10); //Створюю і вивожу інформативний блок з таблицею $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Table->Show());
									echo $TableInfo->Show();
                */
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