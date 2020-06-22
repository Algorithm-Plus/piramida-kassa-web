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
					echo "<TD align=center width=".$WidthPage."px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-yx;' background='template/".$_SESSION["Template"]."/img/bkground1.jpg'>";
//---------------------------Дані на сторінці
						echo "<form name='defecturaedit' METHOD=post ACTION='tov_def_ins.php'>";
						echo "<BR>";
						echo "<table align='center'>";

						//Таблиця з дефектурою
							echo "<TR>";
								echo "<TD align='center'>";
									//echo "<font class='Warning'>".$FormTovarItem9."</font><BR><BR><BR>";
$def = array(
  array("ID","C",10),
  array("NAME","C",100),
  array("QUANT","N",18, 8),
);
$DBFFileName='defektura'.$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->MnemoCod.'.dbf';
dbase_create($DBFFileName, $def);
$dbf = dbase_open($DBFFileName,2);

									$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
									//$TableData = $DataModule->GetAllTovarWithSale($_REQUEST["Date1"],$_REQUEST["Date2"], $_REQUEST["Period"]);
									$TableData = $DataModule->GetAllTovarWithSale($_REQUEST["Period"]);
									echo "<font class='Midle'>".$FormTovarItem5."</font>";
									$Table = new Table();
									echo $Table->TableBegin(count($TableData[1]));
									$Row = array($FormTovarDefecturaItem1, $FormTovarItem2, $FormTovarDefecturaItem4, $FormTovarDefecturaItem8, $FormTovarOborotItem6." ".$FormTovarDefecturaItem8, $FormTovarDefecturaItem10);
									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);	
									
									$Table->ChangeAlign(3,'Right');
									$Table->ChangeAlign(4,'Right'); //Колонки з к-тю по правому краю
									$Table->ChangeAlign(5,'Right');
									$Table->ChangeAlign(6,'Right');
													

									for ($i=0; $i<count($TableData); $i++)
									{
										$TableRow = $TableData[$i];
										/*
										$Value ="
											<input TYPE=text class='Edit' SIZE=5 name='vol".$i."' VALUE=".$TableRow[6].">
											<INPUT type=hidden name='id".$i."' value=".$TableRow[7].">
										";
										*/
									
										if ($TableRow[3]+$TableRow[4]+$TableRow[5]!=0){
										$Row = array($TableRow[1], $TableRow[2], number_format($TableRow[3],2,",",""), number_format($TableRow[4],2,",",""), number_format($TableRow[5],2,",",""), number_format($TableRow[6],2,",",""));
										echo $Table->TableRow($Row);
										
										dbase_add_record($dbf, array(
											$TableRow[1], 
											$TableRow[2], 
											$TableRow[6] 
										)); 
										}
										
									}
									
									echo $Table->TableEnd();
									echo $Table->ShowMenu();

if ($dbf) {
dbase_close($dbf);
}

								echo "</TD>";
							echo "</TR>";
						echo "</table>";
						
						echo "<INPUT type=hidden name='RecCount' value=".count($TableData).">"; //Кількість записів (щоб знову не шукати)
						//echo "<INPUT class='ButtonPasive' TYPE='Submit'  value='".$FormTovarItem8."'>";
						
						//echo "</p>";
						echo "</form>";
						

echo "<form name='defecturasave' METHOD=post ACTION='tov_def_savedbf.php?file=".$DBFFileName."'>";
	echo "<INPUT class='ButtonPasive' TYPE='Submit'  value='".$FormTovarItem23."'>";
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