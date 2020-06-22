<?php
include ("config.php");

echo "<title>".$FormTovarItem1."</title>";
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
									//$TableData = $DataModule->OborotByParts($_REQUEST["Ident"], $_REQUEST["Date2"]);
									echo "<table border=1>";
										echo "<td>Партія</td><td>Код тов.</td><td>Назва</td><td>Код штриха</td><td>Штрих-код</td><td>Ціна
										</td><td class='TabStyleDark'>К-ть вх.</td><td>Сума вх.</td>
										</td><td class='TabStyleDark'>К-ть пр.</td><td>Сума пр.</td>
										</td><td class='TabStyleDark'>К-ть вих.</td><td>Сума вих.</td>
										</td><td class='TabStyleDark'>К-ть зал.</td><td>Сума зал.</td>
										</td><td class='TabStyleDark'>К-ть реал.</td><td class='TabStyleDark'>К-ть різн.</td>";
									//for ($i=1; $i<=count($TableData); $i++)
									{
										//$TableRow = $TableData[$i];
										echo "<tr>";
											echo "<td rowspan=2>"."TableRow[1]"."</td>";
											echo "<td rowspan=2>"."TableRow[2]"."</td>";
											echo "<td>"."TableRow[3]"."</td>";
											echo "<td rowspan=2>"."TableRow[5]"."</td>";
											echo "<td>"."TableRow[6]"."</td>";
											echo "<td rowspan=2>"."TableRow[8]"."</td>";
											echo "<td rowspan=2 class='TabStyleDark'>"."TableRow[9]"."</td>";
											echo "<td rowspan=2>"."TableRow[10]"."</td>";
											echo "<td rowspan=2 class='TabStyleDark'>"."TableRow[11]"."</td>";
											echo "<td rowspan=2>"."TableRow[12]"."</td>";
											echo "<td rowspan=2 class='TabStyleDark'>"."TableRow[13]"."</td>";
											echo "<td rowspan=2>"."TableRow[14]"."</td>";
											echo "<td rowspan=2 class='TabStyleDark'>"."TableRow[15]"."</td>";
											echo "<td rowspan=2>"."TableRow[16]"."</td>";
											echo "<td rowspan=2 class='TabStyleDark'>"."TableRow[17]"."</td>";
											echo "<td rowspan=2 class='TabStyleDark'>"."TableRow[18]"."</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>"."TableRow[4]"."</td>";
											echo "<td>"."TableRow[7]"."</td>";
										echo "</tr>";
									}
									echo "</table>";
									
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