<?php
include ("config.php");
echo "<script language='JavaScript'>";
	echo "
	function PutBarCode(BarCode)
	{
		window.opener.document.getElementById('Shtrih').value=BarCode;
	}
	";
	//echo "parent.frames['frametop'].location.reload();";
	//echo "alert(sh);";
	//echo "window.opener.oborot.Shtrih.value='123';";
echo "</script>";

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
									$TableData = $DataModule->GetOborot($_REQUEST["Shtrih"],$_REQUEST["Date1"],$_REQUEST["Date2"], $_REQUEST["Ident"]);
									
									$TableRow = $TableData[1];
									$TovName = $TableRow[3];
									//echo "<font class='Midle'>".$TableRow[3]."</font>";
									$Table = new Table();
									$Row = array("", "", "", "", "", $FormTovarOborotItem6, $FormTovarOborotItem7, $FormTovarOborotItem8);
									$Table->AddRow($Row);
									//$Table->LastRowIsTableHeader();//Останній доданий рядок був заголовком в таблиці
									for ($i=2; $i<=count($TableData); $i++)
									{
										$TableRow = $TableData[$i];

										if ($TableRow[3]=="ПРИХІД ТОВАРУ:")
										{
											$DocType=1;
										}
										if ($TableRow[3]=="ПРОДАЖІ:")
										{
											$DocType=2;
										}
										if ($TableRow[3]=="ПЕРЕДАЧА ТОВАРУ:")
										{
											$DocType=3;
										}
										
										if ($TableRow[3]==null)
										{
											if ($DocType==1)//Треба вставляти ссилку в залежності від документа
											{
												$Link = "<a class='SimpleColor' target='_blank' href='get_doc.php?id=". $TableRow[9]."&type=import'>". $TableRow[1]."</a>";	
											}
											if ($DocType==2)
											{
												$Link = "<a class='SimpleColor' target='_blank' href='get_check.php?id=". $TableRow[1]."&type=import'>". $TableRow[1]."</a>";
											}
											if ($DocType==3)
											{
												$Link = "<a class='SimpleColor' target='_blank' href='get_doc.php?id=". $TableRow[1]."&type=export'>". $TableRow[1]."</a>";
											}
										}
										else
										{
											$Link = $TableRow[1];
										}

										$Row = array($Link,$TableRow[2],$TableRow[3],$TableRow[4],$TableRow[5],$TableRow[6],$TableRow[7],$TableRow[8]);
										$Table->AddRow($Row);
										//Затоную рядкі з сумами по розділам
										if (($TableRow[3]=="ВСЬОГО ПО ПРИХОДУ:")||
											($TableRow[3]=="ВСЬОГО ПРОДАНО:")||
											($TableRow[3]=="ВСЬОГО ПО ПЕРЕДАЧІ:")||
											($TableRow[3]=="ЗАЛИШОК ТОВАРУ:"))
										{
											$Table->ChangeLastRowDark();
										}
									}
									$Table->ChangeAlign(6,'Right');
									$Table->ChangeAlign(7,'Right');
									$Table->ChangeAlign(8,'Right'); //Колонки з к-тю по правому краю

									$TableInfo = new TableInfo("<b>".$TovName."</b> ===== ".$From." ".$_REQUEST["Date1"]." ".$To." ".$_REQUEST["Date2"]); //Створюю і вивожу інформативний блок з таблицею $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Table->Show());
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