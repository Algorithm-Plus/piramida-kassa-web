<?php
include ("config.php");
$_SESSION["CurrentSection"]=1;
echo "<script language='JavaScript'>";
	echo "parent.frames['frametop'].location.reload();";
echo "</script>";

echo "<BODY class='Default'>";
echo "<LINK REL='stylesheet' href='template/".$_SESSION["Template"]."/style.css' type='text/css'>";
//Кожні n-сек оновлюю сторінку
echo "<META HTTP-EQUIV='Refresh' CONTENT='".$FirsPageRefresh."; URL=firstpage.php'>";
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
						echo "<font class='Midle'>".date("d.m.Y H:i")."</font>";
						echo "<BR>";
						echo "<table>";

						//Дані по сумах в касах
							echo "<TR>";
									for ($i=1; $i<=Count($_SESSION["AptekaList"]); $i++)
									{
										if ($_SESSION["AptekaList"][$i]->Active==1) //Показуємо тільки аптеки до яких змогли підкоктитись
										{
											echo "<TD>"; //$i=1;
											$DataModule = new DataModule($_SESSION["AptekaList"][$i]);
											$Sum1 = $DataModule->GetTotalReceiptsSum((date("d")-1).".".date("m").".".date("Y")." 00:00:00", (date("d")-1).".".date("m").".".date("Y")." 23:59:59");
											$Sum2 = $DataModule->GetTotalReceiptsSum(date("d.m.Y")." 00:00:00", date("d.m.Y")." 23:59:59");
											$Sum3 = $DataModule->GetTotalReceiptsSum(date("d.m.Y")." ".$glShiftBegin, date("d.m.Y")." 23:59:59");

											$Table = new Table();
											$Row = array(' ', $TableYesterday, $TableToday, $TableShift2);
											$Table->AddRow($Row);
									//$Table->ChangeLastRowDark();
									$Table->LastRowIsTableHeader();//Останній доданий рядок був заголовком в таблиці
                  
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

                  $Row = array($FormKassaItem4, $Sum1[6], $Sum2[6], $Sum3[6]);
									$Table->AddRow($Row);
                  $Row = array($FormKassaItem2, $Sum1[4], $Sum2[4], $Sum3[4]);
									$Table->AddRow($Row);
                   $Row = array($FormKassaItem9, $Sum1[10], $Sum2[10], $Sum3[10]);
									$Table->AddRow($Row);
									
											$Table->ChangeAlign(2,'Right'); //Колонки з сумами по правому краю
											$Table->ChangeAlign(3,'Right');
											$Table->ChangeAlign(4,'Right');

											$TableInfo = new TableInfo($FormKassaTitle1." ".$AptekaList[$i]->FullName); //Створюю і вивожу інформативний блок з таблицею $Table->Show()
											$TableInfo->ChangeBorder(2);
											$TableInfo->AddBody($Table->Show());
											echo $TableInfo->Show();
											echo "</TD>";
										}
										if ($i%3==0)
											{
												echo "</tr></tr>";
											}
									}
							echo "</TR>";

							
							//Кількість чеків, тобто кількість клієнтів
							echo "<TR>";
								for ($i=1; $i<=Count($_SESSION["AptekaList"]); $i++)
									{
										if ($_SESSION["AptekaList"][$i]->Active==1) //Показуємо тільки аптеки до яких змогли підкоктитись
										{
											echo "<TD>";
												$DataModule = new DataModule($_SESSION["AptekaList"][$i]);
												$Cnt1 = $DataModule->GetReceiptsCount((date("d")-1).".".date("m").".".date("Y"), (date("d")-1).".".date("m").".".date("Y"));
												$Cnt2 = $DataModule->GetReceiptsCount(date("d.m.Y"), date("d.m.Y"));

												$Table = new Table();
												$Row = array($TableYesterday, $TableToday);
												$Table->AddRow($Row);
												$Table->LastRowIsTableHeader();
												$Row = array($Cnt1, $Cnt2);
												$Table->AddRow($Row);
												$Table->ChangeAlign(1,'Center');
												$Table->ChangeAlign(2,'Center');

												$TableInfo = new TableInfo($FirstPageItem1." ".$_SESSION["AptekaList"][$i]->FullName);
												$TableInfo->ChangeBorder(2);
												$TableInfo->AddBody($Table->Show());
												echo $TableInfo->Show();
												echo "</TD>";
										}
										if ($i%3==0)
											{
												echo "</tr></tr>";
											}
									}
							echo "</TR>"; 
/*
							//Останні продані позиції
							echo "<TR>";
								for ($i=1; $i<=Count($_SESSION["AptekaList"]); $i++)
									{
										if ($_SESSION["AptekaList"][$i]->Active==1)
										{
											echo "<TD>";
												$DataModule = new DataModule($_SESSION["AptekaList"][$i]);
												$result = $DataModule->GetLastPreparats(5);

												$Table = new Table();
												$Row = array($FormPreparatItem1, $FormPreparatItem2, $FormPreparatItem3);
												$Table->AddRow($Row);
												$Table->LastRowIsTableHeader();

												while ($rowresult=ibase_fetch_assoc($result))
												{
													$Row = array($rowresult["SNOMEN_NAME"], $rowresult["NQUANT"], $rowresult["NPRICE"]);
													$Table->AddRow($Row);
												}

												$Table->ChangeAlign(2,'Right');
												$Table->ChangeAlign(3,'Right');

												$TableInfo = new TableInfo($FirstPageItem2." ".$_SESSION["AptekaList"][$i]->FullName);
												$TableInfo->ChangeBorder(2);
												$TableInfo->AddBody($Table->Show());
												echo $TableInfo->Show();
												echo "</TD>";
										}
									}
							echo "</TR>"; 
*/						
						echo "</table>";

						//Якщо незмогли до якоїсь бази підключитися - то інформуємо про це
						for ($i=1; $i<=Count($_SESSION["AptekaList"]); $i++)
						{
							if ($_SESSION["AptekaList"][$i]->Active==0)
							{
								echo "<BR>";
								echo "<img valign='middle' src=template/".$_SESSION["Template"]."/img/warning.png><font class='Warning'>".$ConnectWarning." ".$_SESSION["AptekaList"][$i]->FullName."</font>";
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