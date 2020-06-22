<?php
include ("config.php");

echo "<title>".$FormTovarTitle1."</title>";
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
						echo "<form name='def' id='def' method=post target='' action='tov_def_ins.php'>";
						echo "<table align='center'>";

						//Таблиця ходовим товаром
							echo "<TR>";
								echo "<TD align='center'>";

$def = array(
  array("ID","C",10),
  array("NAME","C",100),
  array("QUANT","N",18, 8),
);
$DBFFileName='def'.$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->MnemoCod.'.dbf';
unlink($DBFFileName);
dbase_create($DBFFileName, $def);
$dbf = dbase_open($DBFFileName,2);
//dbase_add_record($dbf, array(0, 'тест',	0)); 
								
									$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
									$TableData = $DataModule->GetZakaz();
									echo "<font class='Midle'>".$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->FullName."</font><BR>";
									
									$Table = new Table();
									echo $Table->TableBegin(5);
									$Row = array($FormTovarDefecturaItem2, $FormPreparatItem2, $FormTovarDefecturaItem3, $FormDiscountHeader18, $FormNakladniHeader2_3);
									$Table->ChangeAlign(2,'Right');
									$Table->ChangeAlign(3,'Right');

									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
									$TableRow[1] = 0; //Якщо таблиця пуста, щоб не було помилки
									for ($i=1; $i<=count($TableData); $i++)
									{
										$TableRow = $TableData[$i];
										$Value ="
											<input TYPE=text class='Edit' SIZE=5 name='vol".$i."' VALUE=".$TableRow[5].">
											<INPUT type=hidden name='id".$i."' value=".$TableRow[2].">
										";
										$Row = array($TableRow[3], $TableRow[4], $Value, $TableRow[6], $TableRow[7]);
										echo $Table->TableRow($Row);
										
										dbase_add_record($dbf, array(
											$TableRow[2], 
											//iconv("utf-8", "windows-1251", $TableRow[3]),
											$TableRow[3],
											//'тест'.$TableRow[3],
											1
											)); 
									}
									echo $Table->TableEnd();
if ($dbf) {
	dbase_close($dbf);
}
								echo "</TD>";

							echo "</TR>";
						echo "</table>";
						echo "<INPUT type=hidden name='RecCount' value=".count($TableData).">"; 
						echo "</form>";
						echo "<BR>";
						
						echo "<form name='defecturasave' id='defecturasave' METHOD=post ACTION='tov_def_savedbf.php?file=".$DBFFileName."'>";
						echo "</form>";
						
						echo "<form name='zero' method=post target='' action='tov_def_zakaz_dell.php'>";
							echo "<INPUT type=hidden name=last_id value=".$TableRow[1].">";
							echo "<p align=center>";
								echo "<input type=Submit class='ButtonPasive' value='".$FormTovarDefecturaItem11."'>";
								echo "&nbsp;&nbsp;";
								echo "<input type=Button class='ButtonPasive' value='".$FormTovarDefecturaItem13 ."' onClick='document.getElementById(\"def\").submit();'>";
								echo "&nbsp;&nbsp;";
								echo "<INPUT class='ButtonPasive' TYPE='Button'  value='".$FormTovarItem23."' onClick='document.getElementById(\"defecturasave\").submit();'>";
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