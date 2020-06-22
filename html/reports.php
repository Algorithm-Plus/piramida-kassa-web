<?php
include ("config.php");
$_SESSION["CurrentSection"]=6;
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
						echo "<table align='center' cellspacing='20' border=0>";
							echo "<TR>";
							//Страхові компанії
								echo "<TD valign='top'>";
									echo "<table>";
									echo "<tr><td>";
										echo "<a href='report_strah1_form.php'><img src='template/".$_SESSION["Template"]."/img/rep_strah1.png' border=0 alt='".$FormReportAlt1."'></a>";
									echo "</td></tr>";
									echo "<tr><td align='center'>";
										echo "<font class='Midle'>".$FormReportItem1."</font>";
									echo "</td></tr>";
									echo "</table>";
								echo "</TD>";
							//СМС по виручці за вчора
								echo "<TD valign='top'>";
									echo "<table>";
									echo "<tr><td>";
										echo "<a href='/cron/sms.php'><img src='template/".$_SESSION["Template"]."/img/rep_sms.png' border=0 alt='".$FormReportAlt2."'></a>";
									echo "</td></tr>";
									echo "<tr><td align='center'>";
										echo "<font class='Midle'>".$FormReportItem2."</font>";
									echo "</td></tr>";
									echo "</table>";
								echo "</TD>";
								//Оборотка по всіх аптеках
								echo "<TD valign='top'>";
									echo "<table>";
									echo "<tr><td align=center>";
										echo "<a href='tov_oborot.html'  target='_blank'><img src='template/".$_SESSION["Template"]."/img/rep_oborot.png' border=0 alt='".$FormReportAlt4."'></a>";
									echo "</td></tr>";
									echo "<tr><td align='center'>";
										echo "<font class='Midle'>".$FormReportItem4."</font>";
									echo "</td></tr>";
									echo "</table>";
								echo "</TD>";
                //Оборотка по всіх аптеках минулого місяця
								echo "<TD valign='top'>";
									echo "<table>";
									echo "<tr><td align=center>";
										echo "<a href='tov_oborot_previous_month.html'  target='_blank'><img src='template/".$_SESSION["Template"]."/img/rep_oborot.png' border=0 alt='".$FormReportAlt4."'></a>";
									echo "</td></tr>";
									echo "<tr><td align='center'>";
										echo "<font class='Midle'>".$FormReportItem21."</font>";
									echo "</td></tr>";
									echo "</table>";
								echo "</TD>";
                
                echo "</TR>";
							echo "<TR>";
              
								//Залишки з цінами
								echo "<TD valign='top'>";
									echo "<table>";
									echo "<tr><td align=center>";
										echo "<a href='tov_price.html' target='_blank'><img src='template/".$_SESSION["Template"]."/img/rep_price.png' border=0 alt='".$FormReportAlt5."'></a>";
									echo "</td></tr>";
									echo "<tr><td align='center'>";
										echo "<font class='Midle'>".$FormReportItem5."</font>";
									echo "</td></tr>";
									echo "</table>";
								echo "</TD>";
							
								//Звіт керівника offline
								echo "<TD valign='top'>";
									echo "<table>";
									echo "<tr><td align=center>";
										echo "<a href='report_tovar_offline.php' target='_blank'><img src='template/".$_SESSION["Template"]."/img/rep_tovar_offline.png' border=0 alt='".$FormReportAlt7."'></a>";
                    //echo "<a href='cron/report_tovar_ap1.php' target='_blank'><img src='template/".$_SESSION["Template"]."/img/rep_tovar_offline.png' border=0 alt='".$FormReportAlt7."'></a>";
									echo "</td></tr>";
									echo "<tr><td align='center'>";
										echo "<font class='Midle'>".$FormReportItem7."</font>";
									echo "</td></tr>";
									echo "</table>";
								echo "</TD>";
								//Звіт по аналітикам
								echo "<TD valign='top'>";
								  echo "<table>";
									echo "<tr><td align=center>";
										echo "<a href='report_analitic_form.php' target='_blank'><img src='template/".$_SESSION["Template"]."/img/rep_anl.png' border=0 alt='".$FormReportAlt13."'></a>";
									echo "</td></tr>";
									echo "<tr><td align='center'>";
										echo "<font class='Midle'>".$FormReportItem13."</font>";
									echo "</td></tr>";
									echo "</table>";
								echo "</TD>";
								//Топ БАДМ
								echo "<TD valign='top'>";
									echo "<table>";
									echo "<tr><td align=center>";
										echo "<a href='report_top_form.php' target='_blank'><img src='template/".$_SESSION["Template"]."/img/rep_anl.png' border=0 alt='".$FormReportAlt13."'></a>";
									echo "</td></tr>";
									echo "<tr><td align='center'>";
										echo "<font class='Midle'>".$FormReportItem20."</font>";
									echo "</td></tr>";
									echo "</table>";
								echo "</TD>";
							echo "</TR>";
              
              
              echo "<TR>";
              
								//Залишки по лікарям
								echo "<TD valign='top'>";
									echo "<table>";
									echo "<tr><td align=center>";
										echo "<a href='report_doctor_form.php' target='_blank'><img src='template/".$_SESSION["Template"]."/img/rep_doctor.png' border=0 alt='".$FormReportItem2."'></a>";
									echo "</td></tr>";
									echo "<tr><td align='center'>";
										echo "<font class='Midle'>".$FormReportItem22."</font>";
									echo "</td></tr>";
									echo "</table>";
								echo "</TD>";
							
								
								echo "<TD valign='top'>";
								
                
								echo "</TD>";
								
								echo "<TD valign='top'>";
								 
								echo "</TD>";
								
								echo "<TD valign='top'>";
								
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