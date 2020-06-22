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
						echo "<form name='defecturaedit' METHOD=post ACTION='tov_def_ins.php'>";
						echo "<BR>";
						echo "<table align='center'>";

						//Таблиця з дефектурою
							echo "<TR>";
								echo "<TD align='center'>";
									echo "<font class='Warning'>".$FormTovarItem9."</font><BR><BR><BR>";

									$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
									$TableData = $DataModule->GetAllTovar();
									
									echo "<font class='Midle'>".$FormTovarItem5."</font>";
									$Table = new Table();
									echo $Table->TableBegin(count($TableData[1])+1);
									$Row = array($FormTovarDefecturaItem1, $FormTovarDefecturaItem2, $FormTovarDefecturaItem5, $FormTovarDefecturaItem6);
									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);	
									
									$Table->ChangeAlign(3,'Center'); //Колонки з к-тю по правому краю
									$Table->ChangeAlign(4,'Center');

									for ($i=1; $i<=count($TableData); $i++)
									{
										$TableRow = $TableData[$i];
										$Value ="
											<input TYPE=text class='Edit' SIZE=5 name='vol".$i."' VALUE=".$TableRow[3].">
											<INPUT type=hidden name='id".$i."' value=".$TableRow[1].">
										";
										$Row = array($TableRow[1], $TableRow[2], $TableRow[3], $Value);
										echo $Table->TableRow($Row);
									}
									
									echo $Table->TableEnd();
									echo $Table->ShowMenu();

								echo "</TD>";
							echo "</TR>";
						echo "</table>";
						echo "<p align='center'>";
						echo "<INPUT type=hidden name='RecCount' value=".count($TableData).">"; //Кількість записів (щоб знову не шукати)
						echo "<INPUT class='ButtonPasive' TYPE='Submit'  value='".$FormTovarItem8."'>";
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