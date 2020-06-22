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
                                                    //$TableInsurer = $DataModule->GetAllInsurer();
                                                    $Form = "
                                                        <form name='report_strah1' method=post target='_blank' action='report_doctor_result.php'>
                                                        
                                                            <font> ".$From." </font><input type=text class='Date' name='Date1' value='01.".date("m", mktime(0, 0, 0, date("m")-1  , date("d"), date("Y"))).".".date("Y")."'><font>".
                                                            $To."</font><input type=text class='Date' name='Date2' value='".date("t",mktime(0, 0, 0, date("m")-1  , date("d"), date("Y"))).".".date("m", mktime(0, 0, 0, date("m")-1  , date("d"), date("Y"))).".".date("Y")."'>
                                                            <BR><BR>
																													  ".
																														/*
                                                    $Form = $Form."
                                                            <SELECT class='Edit 200' name='Insurer'>";
                                                    for ($i=1; $i<=count($TableInsurer); $i++)
                                                    {
                                                        $Form = $Form."
                                                            <OPTION VALUE='".$TableInsurer[$i][2]."'>".$TableInsurer[$i][3];
                                                    }
                                                    $Form = $Form."
                                                            </SELECT>
                                                            <BR>";
																														*/
                                                            "<input type=Submit class='ButtonPasive' value='".$FormKassaItem6."'>
                                                            </form>";
                                                    $TableInfo = new TableInfo($FormKassaTitle12);
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