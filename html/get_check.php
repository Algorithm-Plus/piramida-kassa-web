<?php
include ("config.php");
$_SESSION["CurrentSection"]=4;

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
						echo "<font class='Midle'>".$AptekaList[$_SESSION["CurrentApteka"]]->FullName."</font>";
						echo "<BR>";
						echo "<table>";

							echo "<TR valign='top'>";
							//Чекі за дату
								echo "<TD>";

									$DataModule = new DataModule($AptekaList[$_SESSION["CurrentApteka"]]);
									$TableData = $DataModule->GetCheckById($_REQUEST["id"]);

									$Table = new Table();
									$Row = array($FormCheckHeader2_1, $FormCheckHeader2_2, $FormCheckHeader2_3, $FormCheckHeader2_4, $FormCheckHeader2_5, $FormCheckHeader2_6, $FormCheckHeader2_7, $FormCheckHeader2_8, $FormCheckHeader2_9, $FormCheckHeader2_10);
									$Table->AddRow($Row);
									$Table->LastRowIsTableHeader();//Останній доданий рядок був заголовком в таблиці
									//Першим іде рядок з загальними сумами
									$TableRow = $TableData[1];
									$RowTotal = array($TableRow[1], $TableRow[2], $TableRow[3], $TableRow[4], $TableRow[5], $TableRow[6], $TableRow[7], $TableRow[8], $TableRow[9]);
									//Потім вигрібаємо дані
									for ($i=2; $i<=count($TableData); $i++)
									{
										$TableRow = $TableData[$i];
										$Row = array($TableRow[1], $TableRow[5], $TableRow[2], $TableRow[9], $TableRow[3], $TableRow[6], $TableRow[7], $TableRow[10], $TableRow[8], $TableRow[4]);
										$Table->AddRow($Row);
									}
									//Добавляємо рядок з загальними сумами
									$Row = array("","","","","","",$RowTotal[5],$RowTotal[6],"","");
									$Table->AddRow($Row);
									$Table->ChangeLastRowDark();

									$Table->ChangeAlign(4,'Right'); //Колонки з сумами по правому краю
									$Table->ChangeAlign(6,'Right');
									$Table->ChangeAlign(7,'Right');
									$Table->ChangeAlign(8,'Right');

									$TableInfo = new TableInfo($FormCheckItem3." ".$RowTotal[1]." за ".$RowTotal[0]." ".$FormCheckItem4." ".$RowTotal[2]); //Створюю і вивожу інформативний блок з таблицею $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Table->Show());
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