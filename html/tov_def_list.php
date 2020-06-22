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
						echo "<BR>";
$def = array(
  array("ID","C",10),
  array("NAME","C",100),
  array("QUANT","N",18, 8),
);
$DBFFileName='def'.$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->MnemoCod.'.dbf';
dbase_create($DBFFileName, $def);
$dbf = dbase_open($DBFFileName,2);

						echo "<table align='center'>";

						//Таблиця з дефектурою
							echo "<TR>";
								echo "<TD align='center'>";
									$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
									$TableData = $DataModule->DefecturaList();
									
									echo "<font class='Midle'>".$FormTovarItem5."</font>";
									$Table = new Table();
									echo $Table->TableBegin(count($TableData[1]));
									$Row = array($FormTovarDefecturaItem1, $FormTovarDefecturaItem2, $FormTovarDefecturaItem3, $FormTovarDefecturaItem4);
									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
									
									$Table->ChangeAlign(3,'Center'); //Колонки з к-тю по правому краю
									$Table->ChangeAlign(4,'Center');

									for ($i=1; $i<=count($TableData); $i++)
									{
										$TableRow = $TableData[$i];
										$Row = array($TableRow[1], $TableRow[2], $TableRow[3], $TableRow[4]);
										echo $Table->TableRow($Row);
										dbase_add_record($dbf, array(
											$TableRow[1], 
											$TableRow[2],
											$TableRow[3]//-$TableRow[4]
											));
									}
									
									echo $Table->TableEnd();
								echo "</TD>";

							echo "</TR>";
						echo "</table>";
						dbase_close($DBFFileName);
						echo "<form name='defecturasave' id='defecturasave' METHOD=post ACTION='tov_def_savedbf.php?file=".$DBFFileName."'>";
						echo "</form>";
						
						echo "<p align=center>";
								echo "<INPUT class='ButtonPasive' TYPE='Button'  value='".$FormTovarItem23."' onClick='document.getElementById(\"defecturasave\").submit();'>";
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