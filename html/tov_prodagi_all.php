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
						echo "<font class='Midle'>".$FormTovarRadio4."</font>";
						echo "<BR>";
						echo "<table align='center'>";

						//Таблиця з продажами
							echo "<TR>";
								echo "<TD align='center'>";
								
									$ArrayFactory = new ArrayFactory();
									$FieldList = array();
									$FieldList[1] = "VARCHAR(55)";
									$FieldList[2] = "VARCHAR(55)";
									$FieldList[3] = "VARCHAR(55)";
									$FieldList[4] = "VARCHAR(55)";
									$FieldList[5] = "DECIMAL(10,3)";
									$FieldList[6] = "DECIMAL(10,2)";
									$FieldList[7] = "DECIMAL(10,2)";
									$FieldList[8] = "DECIMAL(10,2)";
									$FieldList[9] = "DECIMAL(10,2)";
									//групуємо по полю 1 і 2 і 5
									$GroupField = array();
									$GroupField[1] = 1;
									$GroupField[2] = 2;
									$GroupField[3] = 3;
									$GroupField[4] = 4;
									$GroupField[5] = 6;
									$GroupField[6] = 8;
									//смуємо поле 3 і 4
									$SumField = array();
									$SumField[1] = 5;
									$SumField[2] = 7;
									$SumField[3] = 9;
									
									$OrderField = 4;
									
									$ArrayFactory->TempTableCreate($FieldList);
									
									for ($j=1; $j<=count($_SESSION["AptekaList"]); $j++)
										{
											if ($_SESSION["AptekaList"][$j]->Active==1)
											{
												$DataModule = new DataModule($_SESSION["AptekaList"][$j]);
												$TableData = $DataModule->GetLastPreparatsOnDate($_REQUEST["Date1"], $_REQUEST["Date2"], $_REQUEST["edrpou"]);
												$ArrayFactory->ArrayAdd($FieldList, $TableData);
											}
										}
									
									$TableData = $ArrayFactory->GetArray($FieldList, $GroupField, $SumField, $OrderField);
									
									$ArrayFactory->TempTableDrop();
									
									echo "<font class='Midle'>".$FormTovarProdagi4."</font>";
									//echo "<BR>";
									//echo "<font class='Midle'>".$FormTovarProdagi2."</font>";
									$Table = new Table();
									echo $Table->TableBegin(count($TableData[1]));//Буде або сума готівкою або перерахунок
									$Row = array($FormPreparatItem4, $FormImportHeader10, $FormTovarItem20, $FormTovarProdagiHeader3, $FormTovarProdagiHeader5, $FormTovarProdagiHeader4, $FormTovarProdagiHeader6, $FormPreparatItem10, $FormTovarProdagiHeader7);
									$Table->ChangeAlign(5,'Right');
									$Table->ChangeAlign(6,'Right');
									$Table->ChangeAlign(7,'Right');
									$Table->ChangeAlign(8,'Right');
									$Table->ChangeAlign(9,'Right');

									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
									
									$Sum = 0;
									$SumIn = 0;
									
									for ($i=1; $i<=count($TableData); $i++)
									{	
										$TableRow = $TableData[$i];
										//print_r($TableRow);
										//echo $TableRow[2]." - ".$TableRow[4]." * ".$TableRow[3]." = ".$TableRow[5]."<BR>";
											$Row = array(
																	$TableRow[1],
																	$TableRow[2],
																	$TableRow[3],
																	$TableRow[4],
																	number_format($TableRow[5],3,",",""),
																	number_format($TableRow[6],2,",",""),
																	number_format($TableRow[7],2,",",""),
																	number_format($TableRow[8],2,",",""),
																	number_format($TableRow[9],2,",",""));
											echo $Table->TableRow($Row);
											$Sum = $Sum + $TableRow[7];
											$SumIn = $SumIn + $TableRow[9];
										
									}
									$Row = array("", "", "", $lnTotal, "", "", number_format($Sum,2,",",""),"",number_format($SumIn,2,",",""));
									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
									
									echo $Table->TableEnd();
/*
									//Вивожу безготівкові чекі
									echo "<BR><BR>";
									echo "<font class='Midle'>".$FormTovarProdagi1."</font>";
									echo "<BR>";
									echo "<font class='Midle'>".$FormTovarProdagi3."</font>";
									echo "<BR>";
									echo "<font class='Midle'>".$_REQUEST["Date1"]."</font>";
									$Table = new Table();
									echo $Table->TableBegin(count($TableData[1])-1);//Буде або сума готівкою або перерахунок
									$Row = array($FormPreparatItem4, $FormImportHeader10, $FormTovarProdagiHeader3, $FormTovarProdagiHeader4, $FormTovarProdagiHeader5, $FormTovarProdagiHeader6, $FormKassaItem2, $FormPreparatItem10, $FormTovarProdagiHeader7);
									$Table->ChangeAlign(4,'Right'); //Колонки з к-тю по правому краю
									$Table->ChangeAlign(5,'Right');
									$Table->ChangeAlign(6,'Right');
									$Table->ChangeAlign(7,'Right');
									$Table->ChangeAlign(8,'Right');
									$Table->ChangeAlign(9,'Right');

									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
									
									$Sum = 0;
									
									for ($i=1; $i<=count($TableData); $i++)
									{	
										$TableRow = $TableData[$i];
										if ($TableRow[5]<>0)
										{
											$Row = array($TableRow[9],
																	$TableRow[10],
																	$TableRow[1],
																	number_format($TableRow[2],2,",",""),
																	number_format($TableRow[3],2,",",""),
																	number_format($TableRow[4],2,",",""),
																	number_format($TableRow[7],2,",",""),
																	number_format($TableRow[8],2,",",""),
																	number_format($TableRow[6],2,",",""));
											echo $Table->TableRow($Row);
											$Sum = $Sum + $TableRow[5];
											$SumIn = $SumIn + $TableRow[6];
										}
									}
									$Row = array("", "", $lnTotal, number_format($Sum,2,",",""),"","",number_format($SumIn,2,",",""));
									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
									
									echo $Table->TableEnd();
*/									
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