<?php
include ("config.php");

//define( 'WP_MEMORY_LIMIT', '1024M' );
//define( 'WP_MAX_MEMORY_LIMIT', '1024M' );
//define( 'WP_CACHE', true );

function CheckInArray($InTableData, $InCode)
{
  $result = false;
  if (count($InTableData)>0)
  {
    
  }
}

echo "<title>".$FormTovarTitle1."</title>";
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
						echo "<table align='center' width=90%>";
						//Таблиця 
							echo "<TR>";
								echo "<TD align='center'>";
								$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
								$DataModule->Log('');
								$DataModule->Log('START select list');
								//Збиремо список всіх товарів що нам треба
								$ArrayFactory = new ArrayFactory();
								$FieldList = array();
								$FieldList[1] = "VARCHAR(10)"; //SCODE
								$FieldList[2] = "VARCHAR(50)"; //NAME
								//$FieldList[3] = "DECIMAL(10,3)"; //REST
								$FieldList[3] = "DECIMAL(10,3)"; //divisibility
								$FieldList[4] = "DECIMAL(10,0)"; //DayCount
								$FieldList[5] = "VARCHAR(1)"; //GroupABC
                $FieldList[6] = "VARCHAR(1)"; //GroupXYZ
                $FieldList[7] = "DECIMAL(10,2)"; //Ціна
								
								//групуємо по полю 1 і 2 і 5
								$GroupField = array();
								$GroupField[1] = 1;
								$GroupField[2] = 2;
								$GroupField[3] = 3;
								$GroupField[4] = 5;
                $GroupField[5] = 6;
                $GroupField[6] = 7;
								//$GroupField[5] = 5;

								//смуємо поле 3 і 4
								$SumField = array();
								$SumField[1] = 4;
                //$SumField[2] = 5;
								
								$OrderField = 2;
									
								$ArrayFactory->TempTableCreate($FieldList);	
								
                $TableData = Array();//Для загального списку
//============ Заказ з каси
							if (isset($_REQUEST["ch01"]))
							{
								$DayCount = 7; //На яку к-ть днів розраховуємо
								$TableList = Array(); //Список на замовлення
								$TableData = $DataModule->GetZakaz();
								for ($i=1; $i<=count($TableData); $i++)
								{
									$Sum = array();
									$Sum[1] = $TableData[$i][8];
									$Sum[2] = $TableData[$i][3];
									$Sum[3] = $TableData[$i][9];
									$Sum[4] = $DayCount;
									$Sum[5] = ""; //Група поки не відома
                  $Sum[6] = ""; //Група поки не відома
                  $Sum[7] = 0;
									$TableList[$i]=$Sum;									
								}
								$ArrayFactory->ArrayAdd($FieldList, $TableList);				
								$DataModule->Log('END zakaz');
							}

//============ Які закінчаться за 7 днів
							if (isset($_REQUEST["ch02"]))
							{
								$DayCount = 7;
								$TableList = Array();
								$CodeList = Array();
								$TableData = $DataModule->RestANDSaleForPeriod(date("d.m.Y", strtotime('-'.$DayCount.' day')), date("d.m.Y"), $CodeList);
								$DataModule->log('END RestANDSaleForPeriod = 7');
								for ($i=1; $i<=count($TableData); $i++)
								{
									$Sum = array();
									$Sum[1] = $TableData[$i][2];
									$Sum[2] = $TableData[$i][3];
									$Sum[3] = $TableData[$i][4];
									$Sum[4] = $DayCount;
									$Sum[5] = "";
                  $Sum[6] = "";
                  $Sum[7] = 0;
									$TableList[$i]=$Sum;									
								}
								$ArrayFactory->ArrayAdd($FieldList, $TableList);
							}
							
//============ Залишок яких 0, але які продавалися за 7 днів
						if (isset($_REQUEST["ch03"]))
						{
								$DayCount = 180;
								$TableList = Array(); //Список на замовлення
								$TableData = $DataModule->GetZero(2); //залишки = 0
								$DataModule->log('complit select list = 0 count= '.count($TableData));
								$j=1;
								$DataModule->Connect();
								for ($i=1; $i<=count($TableData); $i++)
								//for ($i=1; $i<=50; $i++)
								{
									//echo memory_get_usage()."<BR>";
									$Rest = 0;
									$Sale = $DataModule->SaleByName($TableData[$i][2], date("d.m.Y", strtotime('-'.$DayCount.' day')), date("d.m.Y")); 
									$DataModule->log('sale=0 '.$i.' '.$TableData[$i][2]);	
									//echo $TableData[$i][2]."  d=".$TableData[$i][4]."  r=".$Rest."   s=".$Sale."<BR>";
									if ($Sale>0)
									{
                    if ($DataModule->CodeInAnalitic('12', $TableData[$i][5])==false)
                    {
                      $Sum = array();
                      $Sum[1] = $TableData[$i][5];
                      $Sum[2] = $TableData[$i][2];
                      $Sum[3] = $TableData[$i][4];
                      $Sum[4] = $DayCount;
                      $Sum[5] = "";
                      $Sum[6] = "";
                      $Sum[7] = 0;
                      $TableList[$j]=$Sum;
                      $j++;
                      unset($Sum);
                    }
									}
								}
								$DataModule->log('END select list = 0');
								$DataModule->DisConnect();
								$ArrayFactory->ArrayAdd($FieldList, $TableList);
						}
			
//============ Аналітика №1
						if (isset($_REQUEST["ch04"]))
						{
								$DayCount = 3;
								$AnaliticCod=12;
								$TableList = Array(); 
								$TableData = $DataModule->ViewAnalitic($AnaliticCod);
								$DataModule->log('complit analitic 1 = '.count($TableData));
								$j=1;
								$DataModule->Connect();
								for ($i=1; $i<=count($TableData); $i++)
								{
									//echo $TableData[$i][2]."  d=".$TableData[$i][4]."  r=".$Rest."   s=".$Sale."<BR>";
									$Sum = array();
									$Sum[1] = $TableData[$i][8];
									$Sum[2] = $TableData[$i][2];
									$Sum[3] = 1; //У вюхах аналітики немає ділимості
									$Sum[4] = $DayCount;
									$Sum[5] = "";
                  $Sum[6] = "";
                  $Sum[7] = 0;
									$TableList[$j]=$Sum;
									$j++;
									unset($Sum);
								}
								$DataModule->log('END select list Anl 1');
								$DataModule->DisConnect();
								$ArrayFactory->ArrayAdd($FieldList, $TableList);
						}
//============ Аналітика №2
						if (isset($_REQUEST["ch05"]))
						{
								$DayCount = 7;
								$AnaliticCod=13;
								$TableList = Array(); 
								$TableData = $DataModule->ViewAnalitic($AnaliticCod);
								$DataModule->log('complit analitic 1 = '.count($TableData));
								$j=1;
								$DataModule->Connect();
								for ($i=1; $i<=count($TableData); $i++)
								{
									//echo $TableData[$i][2]."  d=".$TableData[$i][4]."  r=".$Rest."   s=".$Sale."<BR>";
									$Sum = array();
									$Sum[1] = $TableData[$i][8];
									$Sum[2] = $TableData[$i][2];
									$Sum[3] = 1; //У вюхах аналітики немає ділимості
									$Sum[4] = $DayCount;
									$Sum[5] = "";
                  $Sum[6] = "";
                  $Sum[7] = 0;
									$TableList[$j]=$Sum;
									$j++;
									unset($Sum);
								}
								$DataModule->log('END select list Anl 1');
								$DataModule->DisConnect();
								$ArrayFactory->ArrayAdd($FieldList, $TableList);
						}									

//=====================================================================================================
								//$DayCount = 7;
								
								$TableData = $ArrayFactory->GetArray($FieldList, $GroupField, $SumField, $OrderField);
								$ArrayFactory->TempTableDrop();								
								
								$ArrayFactory = new ArrayFactory(); //Збережу сюди товари і необхідну к-ть для замовлення
								$ArrayFactory->TempTableCreate($FieldList);	
								
								//Вивожу список необхідних товарів
								echo "Необхідно придбати: <BR>";
								$Table = new Table();
                $Table->ChangeAlign(7,'Right');
								$Table->Width = 80;
								echo $Table->TableBegin(count($TableData[1])+1); //+1 на номер по порядку
								$DataModule->Connect();
								$nom=1;
								$DataModule->log('START calculation count= '.count($TableData));
								$CodeList=Array();
								$CodeListRow=''; //Перелік кодів для селекту
								$CodeListCount = 0;
								for ($i=1; $i<=count($TableData); $i++)
								//for ($i=1; $i<=50; $i++)
								{
									$DataModule->log($i);
									$TableRow = $TableData[$i];
									$DayCount=$TableData[$i][4];
									//$Rest = round($DataModule->RestByName($TableData[$i][2], 1)[1][1]*$TableRow[3], 0); //пластин
									$Rest = (float)$DataModule->RestByName($TableData[$i][2], 1)[1][1];
                  //echo $TableData[$i][2].'  '.$Rest."<BR>";
                  $Price = $DataModule->LastPriceByNomenCode($TableData[$i][1])[1];
									$DataModule->log("Rest");
									//К-ть пластин за місяць / 4
									//$Sale = $DataModule->SaleByName($TableData[$i][2], date("d.m.Y", strtotime('-1 month')), date("d.m.Y"))*$TableRow[3]; //пластин
									//Для матриць розібємо по групах
									$GroupABC = '';
                  $GroupXYZ='';
									if (isset($_REQUEST["ch04"]) || isset($_REQUEST["ch05"]))
									{
										//group A
                    $DayCount = 90;
										$SaleTimes = $DataModule->SaleByNameTimes($TableData[$i][2], date("d.m.Y", strtotime('-'.$DayCount.' day')), date("d.m.Y"));
                    $Sale = $DataModule->SaleByName($TableData[$i][2], date("d.m.Y", strtotime('-'.$DayCount.' day')), date("d.m.Y"));
                    //echo $TableData[$i][2].'  r='.$Rest.'  st='.$SaleTimes.'   s='.$Sale."<BR>";
										if ($SaleTimes>=10)
										{
											//echo "A<BR>";
                      $GroupABC="A";                      
                      if ($Price<20)
                      {
                        $Sale = ($Sale/$DayCount)*10;
                      }
                      else
                      {
                        $Sale = ($Sale/$DayCount)*7;
                      }
                      if ($Rest>0)
                      {
                        //echo $TableData[$i][2].' - '.intval($Sale).'<BR>';                        
                        $Need = round((intval($Sale)-$Rest), 0);
                      }
                      else
                      {
                        $Need = 1;
                      }
                      //echo 'n='.$Need.'<br>';
                      
										}
										else
										{
											//group B
                      //$DayCount = 90;
										  //$SaleTimes = $DataModule->SaleByNameTimes($TableData[$i][2], date("d.m.Y", strtotime('-'.$DayCount.' day')), date("d.m.Y"));
                      //$Sale = $DataModule->SaleByName($TableData[$i][2], date("d.m.Y", strtotime('-'.$DayCount.' day')), date("d.m.Y"));
											if (($SaleTimes>=6) && ($SaleTimes<=9))
											{
												//echo "B<BR>";
                        $GroupABC="B";                        
                        if ($Price<350)
                        {
                          $Sale = ($Sale/$DayCount)*5;
                          $Need = round(($Sale-$Rest), 3);
                          if (($Need<1) && ($TableData[$i][3]==1)) //Ділимість 1
                          {
                            $Need=1;
                          }
                        }
                        else
                        {
                          if (($Rest<0.66)&&($Rest>0))
                          {
                            $Need = 0.33;
                          }
                          else
                          {
                            $Need = 0;
                          }                          
                        }	
											}
											else
											{
												//group C
												//$Sale = $DataModule->SaleByNameTimes($TableData[$i][2], date("d.m.Y", strtotime('-30 day')), date("d.m.Y"));
												if (($SaleTimes>=3) && ($SaleTimes<=5))
												{
													//echo "C<BR>";
                          $GroupABC="C";
                          if ($Price<350)
                          {
                            $Sale = ($Sale/$DayCount)*7;
                            $Need = round(($Sale-$Rest), 3);
                            if (($Need<1) && ($TableData[$i][3]==1)) //Ділимість 1
                            {
                              $Need=1;
                            }
                          }
                          else
                          {
                            if (($Rest<0.33)&&($Rest>0))
                            {
                              $Need = 0.66;
                            }
                            else
                            {
                              $Need = 0;
                            }                          
                          }														
												}
                        else
                        {
                          //group X //берем тільки в лікісах
                          //$DayCount = 179;
                          //$SaleTimes = $DataModule->SaleByNameTimes($TableData[$i][2], date("d.m.Y", strtotime('-'.$DayCount.' day')), date("d.m.Y"));
                          if (($SaleTimes>=1) && ($SaleTimes<=2))
                          {
                            $GroupABC="X";
                            if (($Rest<0.5)&&($Rest>0))
                            {
                              $Need = 0.5;
                            }
                            else
                            {
                              $Need = 0;
                            }  
                          }
                          else
                          {
                            //group Y;
                            //$DayCount = 179; //91-180                            
                            $SaleTimes = $DataModule->SaleByNameTimes($TableData[$i][2], date("d.m.Y", strtotime('-180 day')), date("d.m.Y", strtotime('-91 day')));
                            if ($SaleTimes>=1)
                            {
                              $GroupABC="Y";
                              $Need = 0;
                            }
                            else
                            {
                              //group Z;
                              //$DayCount = 179; //181-365                            
                              $SaleTimes = $DataModule->SaleByNameTimes($TableData[$i][2], date("d.m.Y", strtotime('-365 day')), date("d.m.Y", strtotime('-181 day')));
                              if ($SaleTimes>=1)
                              {
                                $GroupABC="Z";
                                $Need = 0;
                              }
                            }                            
                          }
                        }
											}
										}
									}
									else //Список не з матриць розраховуємо з продаж за місяць
									{
										$Sale = $DataModule->SaleByName($TableData[$i][2], date("d.m.Y", strtotime('-1 month')), date("d.m.Y"));
                    $Need = round(($Sale-$Rest), 3);                    
									}
                  //echo '<BR>group: ',$GroupABC.'<BR>';
                  //$TableData[$i][5] = $GroupABC;
									//$Sale = round($Sale, 4);
									//$DataModule->log("Sale");
									//$DataModule->log($i.' '.$TableData[$i][2]);
//									$Need=$Sale-$Rest; //Використаю змінну, щоб було менше дужок
//                  if (
//											($Need<1)&&($Need>0)
//											)
//									{
//										$Need=1;
//									}
//									else
//									{
//									  $Need = round(($Sale-$Rest), 0);
//									}
                  
									//echo $TableRow[2].$GroupABC." r=".$Rest." s=".$Sale." n=".$Need." price=".$Price."<BR>";
									if ($Need>0)
									{	
										$Row = array(
																$nom,
																$TableRow[1],
																$TableRow[2]." r=".$Rest." s=".number_format($Sale,2,",","")." n=".$Need,
																//number_format($TableRow[3],3,",","")
																$Need,
																$GroupABC,
                                $GroupXYZ,
                                number_format($Price,2,",","")
																);
										$ArrayFactory->ArrayAdd($FieldList,
																						array(1=>
																									 array(
																												 1=>$TableRow[1],
																												 2=>$TableRow[2],
																												 3=>$Need,
																												 4=>$TableRow[4],
																												 //5=>$TableRow[5],
                                                         5=>$GroupABC,
                                                         6=>$TableRow[6],
                                                         7=>$Price                                                         
																												 )
																									)
																						);
                    //Будемо шукати в інших аптеках одним запросом і дорожчі за 15 грн
                    if ($Price>15)
                    {
                      $CodeListRow=$CodeListRow." ".$TableRow[1].", ";
                      $CodeListCount=$CodeListCount+1;
                    }  
                    if ($CodeListCount==1400) //1500 обмеження в запросі in для FireBird
                    {
                      //Заберу останню кому
                      $CodeListRow = substr($CodeListRow, 0, strlen($CodeListRow)-2);
                      //echo $CodeListRow."<BR>";
                      $CodeList[]=$CodeListRow;
                      $CodeListCount=0;
                      $CodeListRow='';
                    }
                    										
										echo $Table->TableRow($Row);
										$nom++;
									}
								}
								//echo count($CodeList)."<BR>";
								//echo $CodeListRow."<BR>";
								if (count($CodeList)==0) //якщо к-ть позицій менша 1400 - то вставляємо те що є
								{
									//Заберу останню кому
									$CodeListRow = substr($CodeListRow, 0, strlen($CodeListRow)-2);
									$CodeList[]=$CodeListRow;
								}
								//print_r($CodeList);
								$DataModule->log('END calculation');
								$DataModule->DisConnect();
								echo $Table->TableEnd();
								//echo $Table->ShowMenu();
								
								echo "<BR>";
								//Пошукаємо в іншій аптеці
								$TableData = $ArrayFactory->GetArray($FieldList, $GroupField, $SumField, $OrderField);
                //print_r($TableData); echo '<BR><BR>';
								for ($AptekaID=1; $AptekaID<=count($AptekaList); $AptekaID++)
								{
									if ($AptekaID<>$_SESSION["CurrentApteka"])
									{
										$DataModule = new DataModule($AptekaList[$AptekaID]);
										$DataModule->log('Start find '.$AptekaList[$AptekaID]->FullName);
										$DataModule->CurrentApteka = $AptekaList[$AptekaID];
										$DataModule->Connect();										
										echo $AptekaList[$AptekaID]->FullName."<BR>";
										$DayCount = 7;
										$TableList = $DataModule->RestANDSaleForPeriod(date("d.m.Y", strtotime('-'.$DayCount.' day')), date("d.m.Y"), $CodeList);
										
										$Table = new Table();
                    //$Table->ChangeAlign(1,'Left');
                    //$Table->ChangeAlign(2,'Left');
                    $Table->ChangeAlign(3,'Right');
										$Table->Width = 80;
										echo $Table->TableBegin(count($TableData[1]));
										for ($i=1; $i<=count($TableData); $i++)
										{	
											for ($j=1; $j<=count($TableList); $j++)
											{
												if (trim($TableList[$j][2])==trim($TableData[$i][1])) //Коди свівпадають
												{
													//if (intval($TableList[$j][5]-$TableList[$j][6])>=1) //добавити 350 грн - 0,5 //Залишок - продажі >1
                          //$TableData[$i][3] - потреба
                          if ($TableList[$j][5]-$TableList[$j][6]>=1) //добавити 350 грн - 0,5 //Залишок - продажі >1                         
													{                            
														//if ($TableData[$i][3]>=intval($TableList[$j][5]-$TableList[$j][6])-1)
                            if ($TableList[$j][5]-$TableList[$j][6]>=$TableData[$i][3])
														{
                              echo $Table->TableRow(array($TableData[$i][1], $TableData[$i][2], $TableData[$i][3]));
                              $TableData[$i][3] = 0;
															//echo $Table->TableRow(array($TableData[$i][1], $TableData[$i][2], intval($TableList[$j][5]-$TableList[$j][6])-1));
															//$TableData[$i][3]=$TableData[$i][3]-(intval($TableList[$j][5]-$TableList[$j][6])-1);
                              //echo $Table->TableRow(array($TableData[$i][1], $TableData[$i][2], intval($TableList[$j][5]-$TableList[$j][6])));
															//$TableData[$i][3]=($TableData[$i][3]<0 ? 0 : $TableData[$i][3]-(intval($TableList[$j][5]-$TableList[$j][6])));                             
														}
                            else //В аптеці менше чим нам потрібно
                            {
                              echo $Table->TableRow(array($TableData[$i][1], $TableData[$i][2], $TableList[$j][5]-$TableList[$j][6]));
                              $TableData[$i][3]=$TableData[$i][3]-($TableList[$j][5]-$TableList[$j][6]);
                            }
													}
												}
											}											
										}
										echo $Table->TableEnd();
										echo "<BR><BR>";
										$DataModule->log('End find '.$AptekaList[$AptekaID]->FullName);
									}
								}
								$ArrayFactory->TempTableDrop();
								//print_r($TableData); echo '<BR><BR>';
								//Залишилося замовити в лікісах                
								echo "Замовити в лікісах<BR>";
								$Table = new Table();
                $Table->ChangeAlign(1,'Left');
                $Table->ChangeAlign(2,'Left');
                $Table->ChangeAlign(3,'Right');
								$Table->Width = 80;
                echo '<form name="zakaz" id="zakaz" METHOD=post ACTION="tov_def_savedbf.php" target="_blank">';
								echo $Table->TableBegin(count($TableData[1]));                
                //print_r($TableData);
                $TableList = array(); //Dbкористаю для тих, що не замивив в лікасах
								for ($i=1; $i<=count($TableData); $i++)
								{
									if (($TableData[$i][3]>0) && ($TableData[$i][5]<>"Y")&& ($TableData[$i][5]<>"Z"))
									{
                    if ($TableData[$i][7]<=10)
                    {
                      $TableData[$i][3] = $TableData[$i][3]*10;
                    }
                    if (($TableData[$i][7]>10)&&($TableData[$i][7]<=15))
                    {
                      $TableData[$i][3] = $TableData[$i][3]*5;
                    }
                    //Округляємо в більше сторону
                    $TableData[$i][3] = round(ceil($TableData[$i][3]),0);
										echo $Table->TableRow(array($TableData[$i][1], $TableData[$i][2], $TableData[$i][3]));
                    echo "<INPUT type=hidden name='cod".$i."' value='".$TableData[$i][1]."'>";
                    echo "<INPUT type=hidden name='sname".$i."' value='".$TableData[$i][2]."'>";
                    echo "<INPUT type=hidden name='val".$i."' value='".$TableData[$i][3]."'>";
                    //Видалимо з масиву, щоб вивести те що не замовляється
                    
									}
                  else
                  {
                    $TableList[] = $TableData[$i];
                  }
								}
								echo $Table->TableEnd();
                echo "<INPUT type=hidden id='count' name='count' value='".count($TableData)."'>";
                echo "<INPUT class='ButtonPasive' TYPE='Submit'  value='".$FormTovarItem23."'><BR>";
                echo '</form>';
                
                //print_r($TableList);
                echo '<BR>Не замовлялися<BR>';
                $Table = new Table();
                $Table->ChangeAlign(1,'Left');
                $Table->ChangeAlign(2,'Left');
                $Table->ChangeAlign(3,'Right');
								$Table->Width = 80;
                echo $Table->TableBegin(count($TableList[1]));
                for ($i=0; $i<count($TableList); $i++)
                {
                  if ($TableList[$i][3]>0)
                  {
                    echo $Table->TableRow(array($TableList[$i][1], $TableList[$i][2], $TableList[$i][3]));
                  }
                }
                echo $Table->TableEnd();
								unset($DataModule);
								unset($TableList);			
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