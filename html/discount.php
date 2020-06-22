<?php
include ("config.php");
$_SESSION["CurrentSection"]=8;
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
						echo "<table border=0>";

							echo "<TR valign='top'>";
							
								//Залишкі товару
								echo "<TD>";
									$Form = "
									<form name='discountlist' method=post target='_blank' action='discount_list.php'>
										<p align='left'>
										<INPUT TYPE=radio NAME=Type VALUE='1' CHECKED>".$FormDiscountItem1."<BR>
										<INPUT TYPE=radio NAME=Type VALUE='2'>".$FormDiscountItem2."<BR>
                    <INPUT TYPE=radio NAME=Type VALUE='3'>".$FormDiscountItem3."<BR>
                    <INPUT TYPE=checkbox NAME=AllApteks>".$FormTovarProdagi4."<BR>
										</p>
                    <font> ".$From." </font><input type=text class='Date' name='Date1' value='01.".date("m.Y")."'><font> ".$To."</font><input type=text class='Date' name='Date2' value='".date("d.m.Y")."'>
                    <BR>
										<input type=Submit class='ButtonPasive' value='".$FormTovarItem3."'>
									</form>";
									
									$TableInfo = new TableInfo($FormDiscountTitle1);
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Form);
									echo $TableInfo->Show();
                                  
								echo "</TD>";
                
                //Оборотка
								echo "<TD>";

									$Form = "
									<form name='oborot' id='oborot' method=post target='_blank' action='discount_info.php'>
                  <p align='left'>
										<font> ".$FormDiscountHeader1." </font><input type=text class='Edit 200' id='Shtrih' name='Shtrih' value=''><BR>        
										<INPUT TYPE=radio NAME=Type VALUE='1' CHECKED>".$FormDiscountItem4."<BR>
										<INPUT TYPE=radio NAME=Type VALUE='2'>".$FormDiscountItem5."<BR>
										</p>
										<font> ".$From." </font><input type=text class='Date' name='Date1' value='01.".date("m.Y")."'><font> ".$To."</font><input type=text class='Date' name='Date2' value='".date("d.m.Y")."'>
										<BR>
										<input type=Submit class='ButtonPasive' value='".$FormTovarItem3."'>
									</form>";                  
									
									$TableInfo = new TableInfo($FormDiscountTitle2); 
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Form);
									echo $TableInfo->Show();
                      
								echo "</TD>";
                
                //Привязка до аналітики
								echo "<TD>";
                  $DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
                  $AnaliticList = $DataModule->GetAllAnalitic();
									$Form = "
									<form name='oborot' id='oborot' method=post target='_blank' action='discount_anlink.php'>
                  <p align='left'>
										<font> ".$FormDiscountHeader1." </font><input type=text class='Edit 200' id='card' name='card' value=''><BR>
                    <BR>
										<SELECT class='Edit 200' name='analitic'>";
                    for ($i=1; $i<=count($AnaliticList); $i++)
                    {
                      $Form = $Form."
                        <OPTION VALUE='".$AnaliticList[$i][2]."'>".$AnaliticList[$i][3]." (".$AnaliticList[$i][4].")";
                    }
                    $Form = $Form."
										</SELECT>                    
										</p>
										<BR>
										<input type=Submit class='ButtonPasive' value='".$FormReportHeader3_1."'>
									</form>";                  
									
									$TableInfo = new TableInfo($FormDiscountTitle3); 
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