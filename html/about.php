<?php
include ("config.php");
$_SESSION["CurrentSection"]=7;
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
						echo "<table border=0>";

							echo "<TR valign='top'>";
							//Версия
								echo "<TD>";		
									$TableInfo = new TableInfo($FormAboutHeader1); //Створюю і вивожу інформативний блок з таблицею $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Version);
									echo $TableInfo->Show();
								echo "</TD>";
							//Ревізія
								echo "<TD>";		
									$TableInfo = new TableInfo($FormAboutHeader2); 
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Revision);
									echo $TableInfo->Show();
								echo "</TD>";
							//Мова
								echo "<TD>";		
									$TableInfo = new TableInfo($FormAboutHeader3); 
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($_SESSION["Language"]);
									echo $TableInfo->Show();
								echo "</TD>";
							//Стиль
								echo "<TD>";		
									$TableInfo = new TableInfo($FormAboutHeader4); 
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($_SESSION["Template"]);
									echo $TableInfo->Show();
								echo "</TD>";
							//Зміна
								echo "<TD>";		
									$TableInfo = new TableInfo($FormAboutHeader6); 
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($lnShiftBegin." ".$glShiftBegin."<BR>".$lnShiftEnd." ".$glShiftEnd);
									echo $TableInfo->Show();
								echo "</TD>";
							echo "</TR>";
							echo "<TR>";
								echo "<TD colspan=4>";
									$Table = new Table();
									for ($i=1; $i<=Count($AptekaList); $i++)
									{
										$Row = array($AptekaList[$i]->FullName, $AptekaList[$i]->Address, $AptekaList[$i]->Tel);
										$Table->AddRow($Row);
										if ($AptekaList[$i]->Active==0)
										{
											$Table->ChangeLastRowDark();
										}
									}
									$TableInfo = new TableInfo($FormAboutHeader5); 
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Table->Show());
									echo $TableInfo->Show();
								echo "</TD>";
							echo "</TR>";
						echo "</table>";
						//Якщо незмогли до якоїсь бази підключитися - то інформуємо про це
						for ($i=1; $i<=Count($_SESSION["AptekaList"]); $i++)
						{
							if ($_SESSION["AptekaList"][$i]->Active==0)
							{
								echo "<BR>";
								echo "<img valign='middle' src=template/".$_SESSION["Template"]."/img/warning.png><font class='Warning'>".$ConnectWarning." ".$AptekaList[$i]->FullName."</font>";
							}
						}
						echo "<BR><BR>";
						echo "<a class='Midle' href='http://algorithm-plus.com' target='_blank'>© 2008 Algorithm-Plus.COM</a>";
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