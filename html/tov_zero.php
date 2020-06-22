<?php
include ("page_top.php");
echo "<script language='JavaScript'>";
	echo "
	function PutBarCode(BarCode, Ident)
	{
		window.opener.document.getElementById('Shtrih').value=BarCode;
		window.opener.document.getElementById('Ident').value=Ident;
	}
	";
	
	echo "
		function SaveDBF()
		{
				doc=document.getElementById('zero');
				doc.action='tov_def_savedbf.php';
				doc.submit();
		}
	";

	//Виділяю всі елементи // window.status = i; for (i=0; i<EllementCount; i++)
	echo "
	function SelectAll(ObjStatus,EllementCount)
	{
		window.status = 'start...';
		for (i=1; i<=EllementCount; i++)
		{	
			window.status = i;
			obj =  document.getElementById('ch'+i); 
			obj.checked = ObjStatus.checked;	
		}	
	}
	";
	/*
	function SelectAll()
	{
		if (Chk.name=='chAll')
		{
			var EllementCount =  ".count($TableData).";
			for (i=0; i<EllementCount; i++)
			{
				var obj =  document.getElementsByName('ch'+i); 
				obj[0].disabled = !Chk.checked;
			}
		}
	}
*/
echo "</script>";

//---------------------------Дані на сторінці
						echo "<form name='zero' id='zero' METHOD=post ACTION='tov_oborot_2.php' target='_blank'>";
						echo "<BR>";
						echo "<table align='center'>";

						//Таблиця з залишками
							echo "<TR>";
								echo "<TD align='center'>";
								  $Total = 0; //Загальна сума по позиціям
									$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
									if ($_REQUEST["Type"]==4) //Залишки по всіх аптеках
									{
										//$TableData = $DataModule->GetZeroAllApteks($_SESSION["AptekaList"]);
										$TableData = $DataModule->GetZeroAllApteksWithPrice($_SESSION["AptekaList"]);									
									}
									if ($_REQUEST["Type"]==5) //Залишки з цінами
									{
											$TableData = $DataModule->GetPrice();
											$_SESSION['price'] = $TableData;
									}
									if ($_REQUEST["Type"]<4)
									{
										$TableData = $DataModule->GetZero($_REQUEST["Type"]);
									}
									
									echo "<font class='Midle'>".$FormTovarItem4."<BR>".$FormTovarSeriya2.": ".date("d.m.Y H:i")."<BR>".$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->FullName."</font>";
									//Шапка таблиці
									$Table = new Table();
									$Table->NextRowIsHeader(true);
									
									if ($_REQUEST["Type"]==4) //Залишки по всіх аптеках
									{
										echo $Table->TableBegin(count($TableData[1])+1); //+1 №
										echo $Table->RowStart();
								    echo $Table->AddCellSimple($FormPreparatItem9, "Left");
										echo $Table->AddCellSimple($FormPreparatItem4, "Left");
										echo $Table->AddCellSimple($FormPreparatItem1, "Left");
										for ($i=1; $i<=count($_SESSION["AptekaList"]); $i++)
										{
												if ($_SESSION["AptekaList"][$i]->Active==1)
												{
																echo $Table->AddCellColSpan($_SESSION["AptekaList"][$i]->ButtonName,2);
												}
										}
										echo $Table->RowEnd();
										//echo $Table->TableRow($Row);
										$Row = array("", "", "");
										for ($i=1; $i<=count($_SESSION["AptekaList"]); $i++)
										{
												if ($_SESSION["AptekaList"][$i]->Active==1)
												{
																$Row1 = array($FormTovarOborotItem6, $FormTovarOborotItem7);
																$Row = array_merge($Row,$Row1);
																$Table->ChangeAlign($i*2+2,'Right');
																$Table->ChangeAlign($i*2+3,'Right');
												}
										}										
									}
									
									if ($_REQUEST["Type"]==5) //Залишки з цінами
									{
								    echo $Table->TableBegin(count($TableData[1])+3);//+1 на чекбокс для обороткі +1 на номер
										$Row = array($FormPreparatItem9, $FormPreparatItem4, $FormTovarItem16, $FormPreparatItem1, $FormTovarOborotItem4, $FormTovarOborotItem5, $FormPreparatItem2, $FormPreparatItem3, $FormPreparatItem8, $FormCheckHeader2_7, "");
										$Table->ChangeAlign(5,'Center');
										$Table->ChangeAlign(6,'Center');
										$Table->ChangeAlign(7,'Right');
										$Table->ChangeAlign(8,'Right');
										$Table->ChangeAlign(9,'Right');
										$Table->ChangeAlign(10,'Right');
									}
									if ($_REQUEST["Type"]<4)
									{
										echo $Table->TableBegin(count($TableData[1])+3);//+1 на чекбокс для обороткі +1 для суми в прайсі, +1 для номера п/п
										$Row = array($FormPreparatItem9, $FormPreparatItem4, $FormPreparatItem1, $FormPreparatItem5, $FormPreparatItem2, "", $FormPreparatItem7);
										$Table->ChangeAlign(1,'Center'); //Колонки з к-тю по правому краю
										$Table->ChangeAlign(4,'Right');
										$Table->ChangeAlign(5,'Center');
										$Table->ChangeAlign(6,'Center');
									}
									echo $Table->TableRow($Row);
									
									
									$Table->NextRowIsHeader(false);
									//$Table->AddRow($Row);
									//$Table->LastRowIsTableHeader();//Останній доданий рядок був заголовком в таблиці	
									$nom = 1; //Номер по-порядку
									
									for ($i=1; $i<=count($TableData); $i++)
									{
										$TableRow = $TableData[$i];
										if ($_REQUEST["Type"]==4) //Залишки по всіх аптеках
										{
												$Row = array();
												$Row1 = array($nom);										
												//$Row[0] = $nom;
												for ($j=1; $j<=count($TableRow); $j++)
												{
																//2 знакі після коми
																if ($j>2)
																{
																			$Row[$j-1] = number_format($TableRow[$j],2,",","");	
																}
																else
																{
																				$Row[$j-1] = $TableRow[$j];
																}
																//print_r($Row); echo "<BR>";
																
												}
											//$Row = array($TableRow[1], $TableRow[2], $TableRow[3], $TableRow[4], $TableRow[5]);
											$Row = array_merge($Row1,$Row);
										}
										if ($_REQUEST["Type"]==5) //Залишки з цінами
								    {
								      $BarCode="<a class='SimpleColor' title='".$TableRow[9]."' href='#'>".$TableRow[1]."</a>";
											$CheckBox = "<input type='checkbox' name='ch".$i."' id='ch".$i."'>";
											$Row = array($nom, $BarCode, $TableRow[8], $TableRow[2], $TableRow[3], $TableRow[7], number_format($TableRow[4],3,",",""), number_format($TableRow[5],2,",",""), number_format($TableRow[6],2,",",""),number_format($TableRow[11],2,",",""), $CheckBox);
											$Total = $Total + $TableRow[6];
										}
										if ($_REQUEST["Type"]<4) 
										{
											//$BarCode="<a class='SimpleColor' id='".$TableRow[1]."' href='#".$TableRow[1]."' OnClick='PutBarCode(".$TableRow[1].");'>".$TableRow[1]."</a>";
											$BarCode="<a class='SimpleColor' href='javascript:PutBarCode(".$TableRow[1].", ".$TableRow[5].");'>".$TableRow[1]."</a>";
											$CheckBox = "<input type='checkbox' name='ch".$i."' id='ch".$i."'><INPUT type=hidden id='sh".$i."' name='sh".$i."' value='".$TableRow[1]."'>";
											$Row = array($nom,$BarCode, $TableRow[2], $TableRow[4], $TableRow[3], "", $CheckBox);
										}
										//$Table->AddRow($Row);
										echo $Table->TableRow($Row);
										$nom = $nom + 1;
									}
									
									//echo $Table->Show();
									if ($_REQUEST["Type"]<>4) //Залишки по одній аптеці
									{
										//Чекбокс який виділяє всі товари для оборотки
										$CheckBox = "<input type='checkbox' NAME=chAll OnClick='SelectAll(this,".count($TableData).");' LANGUAGE='Javascript'>";
										$Table->NextRowIsHeader(true);
										$Row = array("","", "", "", "", number_format($Total,2,",",""), $CheckBox);
										echo $Table->TableRow($Row);
										$Table->NextRowIsHeader(false);
									}
									echo $Table->TableEnd();
									echo $Table->ShowMenu();
								echo "</TD>";

							echo "</TR>";
						echo "</table>";
						echo "<p align='center'>";

						if ($_REQUEST["Type"]<>4) //Залишки по одній аптеці, вивожу дати для оборотки
						//if ($_REQUEST["Type"]<4)
						{
							echo "<INPUT type=hidden name='RecCount' value=".count($TableData).">"; //Кількість записів (щоб знову не шукати)
							echo "<font> ".$From." </font><input type=text class='Date' name='Date1' value='01.".date("m.Y")."'><font> ".$To."</font><input type=text class='Date' name='Date2' value='".date("d.m.Y")."'>";
							echo "<BR><BR>";
							echo "<INPUT class='ButtonPasive' TYPE='Submit'  value='".$FormTovarItem11."'><BR>";
						  echo "<INPUT class='ButtonPasive' TYPE='Button'  value='".$FormTovarItem23."' OnClick='SaveDBF();'>";
							echo "</p>";
							echo "</form>";
							
						}
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