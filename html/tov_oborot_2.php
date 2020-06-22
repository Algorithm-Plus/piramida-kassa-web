<?php
// Показуємо обороти з сторінки залишків по всім точкам

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
						echo "<table align='center'>";

						//Таблиця з оборотами
							echo "<TR>";
								echo "<TD align='center'>";
									echo "<font class='Midle'>".$FormTovarItem1."</font>";
									echo "<BR>";
									echo "<font class='Midle'>".$From." ".$_REQUEST["Date1"]." ".$To." ".$_REQUEST["Date2"]."</font>";

									$DataModule = array(); //Для кожної аптеки свій дата модуль.
									for ($i=1; $i<=Count($_SESSION["AptekaList"]); $i++)
									{
										if ($_SESSION["AptekaList"][$i]->Active==1)
										{
											$DataModule[$i] = new DataModule($_SESSION["AptekaList"][$i]);
										}
									}

									$Table = new Table();
									echo $Table->TableBegin(count($DataModule)*4+2); //У кожної активної аптеки буде по 4 колонки + назва товару + штрих

									//Добавляємо шапку
									$Table->NextRowIsHeader(true);
									$Row=array("","");//Клітинка з штрихом і назвою
									echo $Table->RowStart();
									echo $Table->AddCellSimple($FormPreparatItem4, "Left");
									echo $Table->AddCellSimple($FormPreparatItem1, "Left");
									//echo $Table->AddCellRowSpan($FormPreparatItem1,2);
									$RowAptekaName1=array("");
									for ($i=1; $i<=count($_SESSION["AptekaList"]); $i++)
									{
										if ($_SESSION["AptekaList"][$i]->Active==1)
										{
											echo $Table->AddCellColSpan($_SESSION["AptekaList"][$i]->ButtonName,4);
											$Row1=array($FormTovarOborotItem9, $FormTovarOborotItem10, $FormTovarOborotItem11, $FormTovarOborotItem12);
											$Row=array_merge($Row,$Row1);
										}
									}
									echo $Table->RowEnd();
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
									
									//Заповнюємо даними
									for ($i=1; $i<$_REQUEST["RecCount"]; $i++)
									{
										if (isset($_REQUEST["ch".$i]))
										{
											if ($_REQUEST["ch".$i])
											{
												//Починаємо рядок з даними
												echo $Table->RowStart();
												echo $Table->AddCellSimple($_REQUEST["sh".$i], "Left"); //Перша клітинка - штрих-код
												//Отримаємо назву по штрих-коду
												
												$TableData = $DataModule[1]->GetOborot($_REQUEST["sh".$i],$_REQUEST["Date1"],$_REQUEST["Date1"]);
												$TableRow = $TableData[1];
												echo $Table->AddCellSimple($TableRow[3], "Left"); //Друга клітинка - назва препарату
												for ($j=1; $j<=count($_SESSION["AptekaList"]); $j++)
												{
													if ($_SESSION["AptekaList"][$j]->Active==1)
													{	 
														//Отримуємо таблицю з усіма оборотами по одному товару
														$TableData = $DataModule[$j]->GetOborot($_REQUEST["sh".$i],$_REQUEST["Date1"],$_REQUEST["Date2"]);
														for ($k=2; $k<=count($TableData); $k++)
														{
															//echo $k;
															$TableRow = $TableData[$k];//Беремо рядок з таблиці
															if ($TableRow[3]=="ВСЬОГО ПО ПРИХОДУ:")
															{
																echo $Table->AddCellSimple(number_format($TableRow[6],2,",",""), "Right");
															}
															if ($TableRow[3]=="ВСЬОГО ПРОДАНО:")
															{
																echo $Table->AddCellSimple(number_format($TableRow[6],2,",",""), "Right");
															}
															if ($TableRow[3]=="ВСЬОГО ПО ПЕРЕДАЧІ:")
															{
																echo $Table->AddCellSimple(number_format($TableRow[6],2,",",""), "Right");
															}
															if ($TableRow[3]=="ЗАЛИШОК ТОВАРУ:")
															{
																$Table->NextRowIsHeader(true);
																echo $Table->AddCellSimple(number_format($TableRow[6],2,",",""), "Right");
																$Table->NextRowIsHeader(false);
															}

														}
														//echo $_REQUEST["sh".$i];
													}
												}
												echo $Table->RowEnd();
											}
										}
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