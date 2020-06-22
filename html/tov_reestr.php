<?php
include ("config.php");
echo "<script language='JavaScript'>";
	echo "
	function PutBarCode(BarCode)
	{
		window.opener.oborot.Shtrih.value=BarCode;
	}
	";
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
						echo "<BR>";
						echo "<table align='center'>";

						//Таблиця з залишками
							echo "<TR>";
								echo "<TD align='center'>";
									$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
									$TableData = $DataModule->GetRegistration($_REQUEST["Date1"]);
									
									echo "<font class='Midle'>".$FormTovarSeriya1."</font>";
									$Table = new Table();
									echo $Table->TableBegin(count($TableData[1]));
									$Row = array($FormPreparatItem4, $FormPreparatItem1, $FormTovarOborotItem4, $FormTovarSeriya3, $FormTovarSeriya4, $FormPreparatItem2);
									$Table->ChangeAlign(4,'Center'); //Колонки з к-тю по правому краю
									$Table->ChangeAlign(5,'Center');
									$Table->ChangeAlign(6,'Right');

									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
									
									for ($i=1; $i<count($TableData); $i++)
									{
										$TableRow = $TableData[$i];
										//$BarCode="<a class='SimpleColor' id='".$TableRow[1]."' href='#".$TableRow[1]."' OnClick='PutBarCode(".$TableRow[1].");'>".$TableRow[1]."</a>";
										$BarCode="<a class='SimpleColor' href='javascript:PutBarCode(".$TableRow[1].");'>".$TableRow[1]."</a>";
										$Row = array($BarCode, $TableRow[2], $TableRow[6], $TableRow[3], $TableRow[4], $TableRow[5]);
										//$Table->AddRow($Row);
										echo $Table->TableRow($Row);
									}
									echo $Table->TableEnd();
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