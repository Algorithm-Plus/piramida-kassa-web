<?php
include ("config.php");

echo "<title>".$FormReportItem15."</title>";
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
						echo "<form name='zero' METHOD=post ACTION='report_analitic_result11.php' target=''>";
						echo "<BR>";
						echo "<table align='center'>";

						//Таблиця з залишками
							echo "<TR>";
								echo "<TD align='center'>";
								$Total = 0; //Загальна сума по позиціям
								$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
								$TableData = $DataModule->GetAllTovarWidthBarcode();
									
								//echo "<font class='Midle'>".$FormTovarItem4."<BR>".$FormTovarSeriya2.": ".date("d.m.Y H:i")."<BR>".$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->FullName."</font>";
								//Шапка таблиці
								$Table = new Table();
								$Table->NextRowIsHeader(true);
								echo $Table->TableBegin(6);//+1 на чекбокс для обороткі +1 для суми в прайсі, +1 для номера п/п
								$Row = array($FormPreparatItem9, $FormPreparatItem4, $FormTovarItem16, $FormPreparatItem1, $FormTovarItem17, $FormReportHeader3_1);
								$Table->ChangeAlign(1,'Center'); //Колонки з к-тю по правому краю
								//$Table->ChangeAlign(4,'Right');
								//$Table->ChangeAlign(5,'Center');
								//$Table->ChangeAlign(6,'Center');	
								echo $Table->TableRow($Row);
								$Table->NextRowIsHeader(false);
								$nom = 1; //Номер по-порядку
									
									for ($i=1; $i<=count($TableData); $i++)
									{
										$TableRow = $TableData[$i];
										//$BarCode="<a class='SimpleColor' href='javascript:PutBarCode(".$TableRow[1].", ".$TableRow[5].");'>".$TableRow[1]."</a>";
										$BarCode=$TableRow[4];
										$CheckBox = "<input type='checkbox' name='ch".$i."'>
										<INPUT type=hidden id='id_tov".$i."' name='id_tov".$i."' value='".$TableRow[1]."'>
										<INPUT type=hidden id='id_sh".$i."' name='id_sh".$i."' value='".$TableRow[4]."'>
										";
										$Edit = "
												<input TYPE=text class='Edit' SIZE=5 name='val".$i."' AUTOCOMPLETE=OFF>
										";
										$Row = array($nom,$BarCode, $TableRow[5], $TableRow[2], $Edit, $CheckBox);
										echo $Table->TableRow($Row);
										$nom = $nom + 1;
									}
									echo $Table->TableEnd();
									echo $Table->ShowMenu();
								echo "</TD>";

							echo "</TR>";
						echo "</table>";
						echo "<p align='center'>";
						echo "<INPUT type=hidden id='analitic' name='analitic' value='".$_REQUEST["Analitic"]."'>";
						echo "<INPUT type=hidden name='RecCount' value=".count($TableData).">"; //Кількість записів (щоб знову не шукати)
						echo "<BR><BR>";
						echo "<INPUT class='ButtonPasive' TYPE='Submit'  value='".$FormReportItem15."'>";
						echo "</p>";
						echo "</form>";
						
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