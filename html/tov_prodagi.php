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
						echo "<font class='Midle'>".$AptekaList[$_SESSION["CurrentApteka"]]->FullName."</font>";
						echo "<BR>";
						
						echo "<form name='strah' id='strah' METHOD=post ACTION='tov_def_savedbf.php' target='_blank'>";
						
						echo "<table align='center'>";

						//Таблиця з продажами
							echo "<TR>";
								echo "<TD align='center'>";
									$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
									$TableData = $DataModule->GetLastPreparatsOnDate($_REQUEST["Date1"], $_REQUEST["Date2"],"");
//print_r($TableData);									
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
									$FieldList[10] = "DECIMAL(10,2)";
									$FieldList[11] = "VARCHAR(20)";
                  $FieldList[12] = "DECIMAL(10,2)";
									$FieldList[13] = "VARCHAR(20)"; //SERIA
									$FieldList[14] = "DECIMAL(10,0)"; //COD
									
									//групуємо по полю 1 і 2 і 5
									$GroupField = array();
									$GroupField[1] = 1;
									$GroupField[2] = 2;
									$GroupField[3] = 3;
									$GroupField[4] = 4;
									$GroupField[5] = 6;
									$GroupField[6] = 8;
									$GroupField[7] = 10;
									$GroupField[8] = 11;
									$GroupField[9] = 13;
									$GroupField[10] = 14;
									
									//смуємо поле 3 і 4
									$SumField = array();
									$SumField[1] = 5;
									$SumField[2] = 7;
									$SumField[3] = 9;
                  $SumField[4] = 12;
									
									$OrderField = 4;
									
									$ArrayFactory->TempTableCreate($FieldList);
									$ArrayFactory->ArrayAdd($FieldList, $TableData);
									
									$TableData = $ArrayFactory->GetArray($FieldList, $GroupField, $SumField, $OrderField);
									
									$ArrayFactory->TempTableDrop();
									
									echo "<font class='Midle'>".$FormTovarProdagi1."</font>";
									echo "<BR>";
									//echo "<font class='Midle'>".$FormTovarProdagi2."</font>";
									echo "<BR>";
									echo "<font class='Midle'>".$_REQUEST["Date1"]."</font>";
									$Table = new Table();
									echo $Table->TableBegin(count($TableData[1])-1);
									$Row = array($FormPreparatItem4, $FormImportHeader10, $FormTovarItem20, $FormTovarProdagiHeader3, $FormTovarProdagiHeader5, $FormTovarProdagiHeader4, $FormTovarProdagiHeader6, $FormPreparatItem10, $FormTovarProdagiHeader7,$FormCheckHeader2_7, $FormTovarOborotItem2, $FormTovarDefecturaItem4, '');
									$Table->ChangeAlign(5,'Right');
									$Table->ChangeAlign(6,'Right');
									$Table->ChangeAlign(7,'Right');
									$Table->ChangeAlign(8,'Right');
									$Table->ChangeAlign(9,'Right');
									$Table->ChangeAlign(10,'Right');
									$Table->ChangeAlign(11,'Right');
                  $Table->ChangeAlign(12,'Right');
                  
                  $AptekaList = array();
                  if (isset($_REQUEST['with_rest'])){                    
                    foreach ($_SESSION["AptekaList"] as $key => $apteka){                     
                      if ($key<>$_SESSION["CurrentApteka"]){
                        $AptekaList[] = array(
                        'DataModule' => new DataModule($_SESSION["AptekaList"][$key]),
                        'title'=>$apteka->MnemoCod
                        );
                      }
                      
                    }
                    foreach ($AptekaList as $key=> $apteka){
                      $Row[] = $apteka['title'];
                      $Table->ChangeAlign(13+$key,'Right');
                    }
                  }

									$Table->NextRowIsHeader(true);
//print_r($Row);									
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);

									$Sum = 0;
									$SumIn = 0;
									
									//Вивожу готівкові чекі
									//$_SESSION['price'] = $TableData;
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
																	number_format($TableRow[9],2,",",""),
																	number_format($TableRow[10],0,",",""),
																	date('d.m.Y', strtotime($TableRow[11])),
                                  number_format($TableRow[12],2,",",""),
																	'
																		<input type="checkbox" name="ch'.$i.'" value="'.$TableRow[14].'">
																		<input type="hidden" name="quant'.$i.'" value="'.$TableRow[5].'">
																		<input type="hidden" name="sname'.$i.'" value="'.$TableRow[4].'">
																	'
																	);
                      foreach ($AptekaList as $apteka){
                        $apteka['DataModule']->Connect();
                        $Row[] = number_format($apteka['DataModule']->RestByBarCode($TableRow[1]),2,",","");
                        $apteka['DataModule']->Disconnect();
                      }
											echo $Table->TableRow($Row);
											$Sum = $Sum + $TableRow[7];
											$SumIn = $SumIn + $TableRow[9];
									}
									$Row = array("", "", "", $lnTotal, "", "", number_format($Sum,2,",",""),"",number_format($SumIn,2,",",""), '','','','');
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
									$Row = array($FormTovarProdagiHeader3, $FormTovarProdagiHeader4, $FormTovarProdagiHeader5, $FormTovarProdagiHeader6);
									$Table->ChangeAlign(2,'Right'); //Колонки з к-тю по правому краю
									$Table->ChangeAlign(3,'Right');
									$Table->ChangeAlign(4,'Right');

									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
									
									$Sum = 0;
									
									for ($i=1; $i<=count($TableData); $i++)
									{	
										$TableRow = $TableData[$i];
										if ($TableRow[5]<>0)
										{
											$Row = array($TableRow[1], $TableRow[2], $TableRow[3], number_format($TableRow[5],2,",",""));
											echo $Table->TableRow($Row);
											$Sum = $Sum + $TableRow[5];
										}
									}
									$Row = array("", "", $lnTotal, number_format($Sum,2,",",""));
									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
									
									echo $Table->TableEnd();
*/									
								echo "</TD>";

							echo "</TR>";
						echo "</table>";
						
							echo "<INPUT type=hidden name='Date1' value=".$_REQUEST["Date1"].">";
							echo "<INPUT type=hidden name='Date2' value=".$_REQUEST["Date2"].">";
							echo "<INPUT type=hidden name='item_count' value=".count($TableData).">";
							echo "<INPUT class='ButtonPasive' TYPE='Submit'  value='".$FormTovarItem23."'><BR>";
						echo "</form>";
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