<?php
include ("config.php");
$_SESSION["CurrentSection"]=4;

echo "<title>".$FormCheckItem2." ".$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->MnemoCod." ".$From." ".$_REQUEST["Date1"]." ".$To." ".$_REQUEST["Date2"]."</title>";
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
//---------------------------���� �� �������
						echo "<font class='Midle'>".$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->FullName."</font>";
						echo "<BR>";
						echo "<table>";

							echo "<TR valign='top'>";
							//��� �� ����
								echo "<TD>";

									$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
									$TableData = $DataModule->GetCheckList($_REQUEST["Date1"], $_REQUEST["Date2"]);

									$Table = new Table();
									$Row = array($FormCheckHeader1, $FormCheckHeader2, $FormCheckHeader3, $FormCheckHeader4, $FormCheckHeader5, $FormCheckHeader6, $FormCheckHeader7, $FormCheckHeader8, $FormCheckHeader9, $FormCheckHeader10,'');
									$Table->AddRow($Row);
									$Table->LastRowIsTableHeader();//�������� ������� ����� ��� ���������� � �������
									for ($i=1; $i<=count($TableData); $i++)
									{
										$TableRow = $TableData[$i];
										$Link = "<a class='SimpleColor' target='_blank' href='get_check.php?id=". $TableRow[2]."'>". $TableRow[1]."</a>";
										if ($TableRow[10]>0)
										{
											$ChangePayLink ="";
										}
										else
										{
											//$ChangePayLink = "<a class='ButtonPasive' href='check_chpytype.php?id=".$TableRow[2]."' target=_blank>".$FormKassaItem5." </a>";
											$ChangePayLink = "";
										}
										$Row = array($Link, $TableRow[2], $TableRow[3], $TableRow[5], $TableRow[6], $TableRow[7], $TableRow[8], $TableRow[9], number_format($TableRow[10],2,",",""), $TableRow[11], $ChangePayLink);
										$Table->AddRow($Row);
										if (trim($TableRow[11])<>"+") //�� ������������ ���
										{
											$Table->ChangeLastRowDark();
										}
									}

									$TableData = $DataModule->GetTotalReceiptsSum($_REQUEST["Date1"]." 00:00:00", $_REQUEST["Date2"]." 23:59:59");
									$Row = array($lnTotal,"","",$TableData[3],$TableData[4],$TableData[5],$TableData[6],$TableData[7],"","");
									$Table->AddRow($Row);
									$Table->ChangeLastRowDark();

									$Table->ChangeAlign(4,'Right'); //������� � ������ �� ������� ����
									$Table->ChangeAlign(5,'Right');
									$Table->ChangeAlign(6,'Right');
									$Table->ChangeAlign(7,'Right');
									$Table->ChangeAlign(8,'Right');
									$Table->ChangeAlign(9,'Right');
									$Table->ChangeAlign(10,'Center');

									$TableInfo = new TableInfo($FormCheckItem2." ".$_REQUEST["Date1"]." - ".$_REQUEST["Date2"]); //������� � ������ ������������� ���� � �������� $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Table->Show());
									echo $TableInfo->Show();
									echo $Table->ShowMenu();
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