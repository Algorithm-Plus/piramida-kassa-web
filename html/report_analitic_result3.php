<?php
include ("config.php");

echo "<BODY class='Default'>";
echo "<LINK REL='stylesheet' href='template/".$_SESSION["Template"]."/style.css' type='text/css'>";
echo "<table class='NoMargin' border=0 width=100%>";
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
						echo "<font class='Midle'>".$FormTovarProdagi1."<BR>".$From." ".$_REQUEST["Date1"]." ". $To." ".$_REQUEST["Date2"]."</font>";
						echo "<BR>";
						echo "<table align='center'>";

						//Таблиця з продажами
							echo "<TR>";
								echo "<TD align='center'>";
								
								//echo "<BR><BR>";
									$ArrayFactory = new ArrayFactory();
									$FieldList = array();
									$FieldList[1] = "VARCHAR(30)";
									$FieldList[2] = "DECIMAL(10,3)";
									//$FieldList[3] = "DECIMAL(10,3)";
									
									//групуємо по полю 1 і 2 і 5
									$GroupField = array();
									$GroupField[1] = 1;
									
									//смуємо поле 3 і 4
									$SumField = array();
									$SumField[1] = 2;
									//$SumField[2] = 3;
									
									$OrderField = 2;
									
									$ArrayFactory->TempTableCreate($FieldList);
									
									for ($j=1; $j<=count($_SESSION["AptekaList"]); $j++)
										{
											if ($_SESSION["AptekaList"][$j]->Active==1)
											{
												$DataModule = new DataModule($_SESSION["AptekaList"][$j]);
												$TableData = $DataModule->ShowSalesForAnalitic2($_REQUEST["Date1"], $_REQUEST["Date2"], $_REQUEST["Analitic"], 2);                        
												$ArrayFactory->ArrayAdd($FieldList, $TableData);
											}
										}
									
									$TableData = $ArrayFactory->GetArray($FieldList, $GroupField, $SumField, $OrderField);
									
									$ArrayFactory->TempTableDrop();
								
									$Table = new Table();
									//$Table->Width='100';
									echo $Table->TableBegin(count($TableData[1]));//Буде або сума готівкою або перерахунок
									$Row = array(
															 $FormCheckHeader3,
															 $FormTovarOborotItem8);
									$Table->ChangeAlign(2,'Right'); //Колонки з к-тю по правому краю
									
									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
									
									//Вивожу готівкові чекі
									for ($i=1; $i<=count($TableData); $i++)
									{	
										$TableRow = $TableData[$i];
										//print_r($TableRow);
										$Row = array(
																	$TableRow[1],
																	number_format($TableRow[2],2,",",""));
										echo $Table->TableRow($Row);
									}
									echo $Table->TableEnd();
									echo "<BR>";
								
								
//===============================================================================
								
								
									$ArrayFactory = new ArrayFactory();
									$FieldList = array();
									$FieldList[1] = "TIMESTAMP";
									$FieldList[2] = "DECIMAL(10,0)";
									$FieldList[3] = "VARCHAR(30)";
									$FieldList[4] = "VARCHAR(55)";
									$FieldList[5] = "VARCHAR(55)";
									$FieldList[6] = "VARCHAR(100)";
									$FieldList[7] = "VARCHAR(30)";
									$FieldList[8] = "VARCHAR(40)";
									$FieldList[9] = "VARCHAR(30)";                  
									$FieldList[10] = "DECIMAL(10,3)";
									$FieldList[11] = "DECIMAL(10,3)";
									$FieldList[12] = "DECIMAL(10,3)";
									$FieldList[13] = "DECIMAL(3,0)";
                  $FieldList[14] = "VARCHAR(100)";
									$FieldList[15] = "VARCHAR(100)";
									
									
									//групуємо по полю 1 і 2 і 5
									$GroupField = array();
									$GroupField[1] = 1;
									$GroupField[2] = 2;
									$GroupField[3] = 3;
									$GroupField[4] = 4;
									$GroupField[5] = 5;
									$GroupField[6] = 6;
									$GroupField[7] = 7;
									$GroupField[8] = 8;
									$GroupField[9] = 9;
                  $GroupField[10] = 14;
                  $GroupField[11] = 15;
									//смуємо поле 3 і 4
									$SumField = array();
									$SumField[1] = 10;
									$SumField[2] = 11;
									$SumField[3] = 12;
									$SumField[4] = 13;
																		
									$OrderField = 3;
									
									$ArrayFactory->TempTableCreate($FieldList);
									
									for ($j=1; $j<=count($_SESSION["AptekaList"]); $j++)
										{
											if ($_SESSION["AptekaList"][$j]->Active==1)
											{
												$DataModule = new DataModule($_SESSION["AptekaList"][$j]);
												$TableData = $DataModule->ShowSalesForAnalitic2($_REQUEST["Date1"], $_REQUEST["Date2"], $_REQUEST["Analitic"], 1);
                        //echo '<pre>'; print_r($TableData); echo '</pre>';
												$ArrayFactory->ArrayAdd($FieldList, $TableData);
											}
										}
									
									$TableData = $ArrayFactory->GetArray($FieldList, $GroupField, $SumField, $OrderField);
									
									$ArrayFactory->TempTableDrop();
									
									//echo "<font class='Midle'>".$FormTovarProdagi4."</font>";
									echo "<BR>";
									//echo "<font class='Midle'>".$FormTovarProdagi2."</font>";
									$Table = new Table();
									//$Table->Width='100';
									echo $Table->TableBegin(count($TableData[1]));//Буде або сума готівкою або перерахунок
									$Row = array(
															 $FormTovarOborotItem2,
															 $FormTovarProdagiHeader1,
															 $FormCheckHeader3,
															 $FormTovarDefecturaItem2,
															 $FormPreparatItem4,
															 $FormTovarItem16,
															 $FormTovarItem20,
															 $FormTovarOborotItem5,
															 $FormTovarOborotItem4,
															 $FormTovarOborotItem6,
															 $FormTovarOborotItem7,
															 $FormTovarOborotItem8,
															 'Sum',
                               'Value',
                               'Check'
															 );
									$Table->ChangeAlign(10,'Right'); //Колонки з к-тю по правому краю
									$Table->ChangeAlign(11,'Right');
									$Table->ChangeAlign(12,'Right');
									$Table->ChangeAlign(13,'Right');

									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
									
									//Вивожу готівкові чекі
									for ($i=1; $i<=count($TableData); $i++)
									{	
										$TableRow = $TableData[$i];
										//print_r($TableRow);
										$Row = array(
																	$TableRow[1],
																	$TableRow[2],
																	$TableRow[3],
																	$TableRow[4],
																	$TableRow[5],
																	$TableRow[6],
																	$TableRow[7],
																	$TableRow[8],
																	$TableRow[9],                                  
																	number_format($TableRow[10],2,",",""),
																	number_format($TableRow[11],2,",",""),
																	number_format($TableRow[12],2,",",""),
																	number_format($TableRow[13],2,",",""),
                                  $TableRow[14],
                                  $TableRow[15]
                                  );
											echo $Table->TableRow($Row);
									}
									//$Row = array("", "", $lnTotal, number_format($Sum,2,",",""),"","",number_format($SumIn,2,",",""));
									//$Table->NextRowIsHeader(true);
									//echo $Table->TableRow($Row);
									//$Table->NextRowIsHeader(false);
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