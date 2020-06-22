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
						echo "<font class='Midle'>".$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->FullName."</font>";
						echo "<BR>";
						echo "<p align='center'>";
						$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
						$AnaliticList = $DataModule->GetAllAnalitic();
								$Form = "
										<form name='report_anal' id='report_anal' method=post target='_blank' action='report_analitic_result1.php'>
												<BR>
												<SELECT class='Edit 200' name='Analitic'>";
            for ($i=1; $i<=count($AnaliticList); $i++)
						{
								$Form = $Form."
										<OPTION VALUE='".$AnaliticList[$i][2]."'>".$AnaliticList[$i][3]." (".$AnaliticList[$i][4].")";
						}
						$Form = $Form."
										</SELECT><BR><BR>";
						$Form = $Form."
												<input type=Button class='ButtonPasive i400' value='".$FormReportItem15."'
														OnClick='
																frm = document.getElementById(\"report_anal\");
																frm.action=\"report_analitic_result1.php\";
																frm.submit();
														'>
												<BR>
												<BR>
												<input type=Button class='ButtonPasive i400' value='".$FormReportItem18."'
														OnClick='
																frm = document.getElementById(\"report_anal\");
																frm.action=\"report_analitic_result2.php\";
																frm.submit();
														'>
												<BR><BR>		
												<font> ".$From." </font><input type=text class='Date' name='Date1' value='01.".date("m", strtotime("-1 month")).".".date("Y")."'>
												<font> ".$To."</font><input type=text class='Date' name='Date2' value='".date("t",strtotime("-1 month")).".".date("m", strtotime("-1 month")).".".date("Y")."'><BR>
												<input type=Button class='ButtonPasive i400' value='".$FormTovarProdagi1."'
														OnClick='
																frm = document.getElementById(\"report_anal\");
																frm.action=\"report_analitic_result3.php\";
																frm.submit();
														'>
                        <BR><BR>
                        <input type=Button class='ButtonPasive i400' value='".$FormPreparatItem7."'
														OnClick='
																frm = document.getElementById(\"report_anal\");
																frm.action=\"report_analitic_oborot.php\";
																frm.submit();
														'>
										</form>";
								$TableInfo = new TableInfo($FormReportItem14);
						    $TableInfo->ChangeBorder(2);
                $TableInfo->AddBody($Form);
						    echo $TableInfo->Show();
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