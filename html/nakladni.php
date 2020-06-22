<?php
include ("config.php");
$_SESSION["CurrentSection"]=5;
echo "<script language='JavaScript'>";
	echo "parent.frames['frametop'].location.reload();";
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
						echo "<font class='Midle'>".$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->FullName."</font>";
						echo "<BR>";
						echo "<table>";

							echo "<TR valign='top'>";
							
								echo "<TD>";
								//Загружені документи
									$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
									$TableData = $DataModule->GetImportDocList(date("d.m.Y"), date("d.m.Y"));

									$Table = new Table();
									$Row = array($FormNakladniHeader1_1, $FormNakladniHeader1_2, $FormNakladniHeader1_3, $FormNakladniHeader1_4, $FormNakladniHeader1_5, $FormNakladniHeader1_6, $FormNakladniHeader1_7, $FormNakladniHeader1_8);
									$Table->AddRow($Row);
									$Table->LastRowIsTableHeader();//Останній доданий рядок був заголовком в таблиці
									for ($i=1; $i<=count($TableData); $i++)
									{
										$TableRow = $TableData[$i];
										$Link = "<a class='SimpleColor' target='_blank' href='get_doc.php?id=". $TableRow[8]."&type=import'>". $TableRow[1]."</a>";
										$Row = array($Link, $TableRow[2], $TableRow[3], $TableRow[4], $TableRow[5], $TableRow[6], $TableRow[7], $TableRow[9]);
										$Table->AddRow($Row);
									}

									$TableData = $DataModule->GetImportDocSum(date("d.m.Y"), date("d.m.Y"));
									$TableRow = $TableData[1];
									$Row = array("","","","",$TableRow[1],$TableRow[2],$TableRow[3],"");
									$Table->AddRow($Row);
									$Table->ChangeLastRowDark();

									$Table->ChangeAlign(5,'Right');
									$Table->ChangeAlign(6,'Right');
									$Table->ChangeAlign(7,'Right');

									$TableInfo = new TableInfo($FormNakladniItem1); //Створюю і вивожу інформативний блок з таблицею $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Table->Show());
									echo $TableInfo->Show();
									echo $Table->ShowMenu();

									echo "<BR>";

								//Вигружені документи
									//$DataModule = new DataModule($AptekaList[$_SESSION["CurrentApteka"]]);
									$TableData = $DataModule->GetExportDocList(date("d.m.Y"), date("d.m.Y"));

									$Table = new Table();
									$Row = array($FormNakladniHeader2_1, $FormNakladniHeader2_2, $FormNakladniHeader2_3, $FormNakladniHeader2_4, $FormNakladniHeader2_5, $FormNakladniHeader2_6, $FormNakladniHeader2_7, $FormNakladniHeader1_8);
									$Table->AddRow($Row);
									$Table->LastRowIsTableHeader();//Останній доданий рядок був заголовком в таблиці
									for ($i=1; $i<=count($TableData); $i++)
									{
										$TableRow = $TableData[$i];
										$Link = "<a class='SimpleColor' target='_blank' href='get_doc.php?id=". $TableRow[8]."&type=export'>". $TableRow[1]."</a>";
										$Row = array($Link, $TableRow[2], $TableRow[3], $TableRow[4], $TableRow[5], $TableRow[6], $TableRow[7], $TableRow[9]);
										$Table->AddRow($Row);
									}

									$TableData = $DataModule->GetExportDocSum(date("d.m.Y"), date("d.m.Y"));
									$TableRow = $TableData[1];
									$Row = array("","","","",$TableRow[1],$TableRow[2],$TableRow[3], "");
									$Table->AddRow($Row);
									$Table->ChangeLastRowDark();

									$Table->ChangeAlign(5,'Right');
									$Table->ChangeAlign(6,'Right');
									$Table->ChangeAlign(7,'Right');

									$TableInfo = new TableInfo($FormNakladniItem2); //Створюю і вивожу інформативний блок з таблицею $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Table->Show());
									echo $TableInfo->Show();
									echo $Table->ShowMenu();
								echo "</TD>";
								echo "<TD valign='top'>";
									$Form = "
									<form name='check' method=post target='_blank' action='nakladnidata.php'>
										<font> ".$From." </font><input type=text class='Date' name='Date1' value='01.".date("m.Y")."'><font>
										".$To."</font><input type=text class='Date' name='Date2' value='".date("d.m.Y")."'>
										<BR>
										<input type=Submit class='ButtonPasive' value='".$FormKassaItem6."'>
									</form>";

									$TableInfo = new TableInfo($FormNakladniItem5);
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Form);
									echo $TableInfo->Show();
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