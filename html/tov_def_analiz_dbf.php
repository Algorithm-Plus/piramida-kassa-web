<?php
include ("config.php");

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
//---------------------------��� �� �������
						echo "<BR>";
						echo $_FILES['dbf']['name']."<BR>";
						echo "<table align='center'>";
						//������� 
							echo "<TR>";
								echo "<TD align='center'>";
								$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
								$DataModule->Connect();
								//print_r($_FILES);
							  $dbf = dbase_open($_FILES['dbf']['tmp_name'],2);
								$Table = new Table();
								echo $Table->TableBegin(8);
								$Row = array($FormPreparatItem9, $FormPreparatItem1, $FormPreparatItem4, $FormTovarOborotItem9, $FormTovarSeriya3, $FormCheckHeader2_6, $FormCheckHeader2_4, "");
								//$Table->ChangeAlign(3,'Right');
								$Table->ChangeAlign(6,'Right');
								$Table->ChangeAlign(7,'Right');
								$Table->NextRowIsHeader(true);
								echo $Table->TableRow($Row);
								$Table->NextRowIsHeader(false);
								$TableRow[1] = 0; //���� ������� �����, ��� �� ���� �������
								$n = 1;
								for ($i=1; $i<=dbase_numrecords($dbf); $i++)
								{
								  $row = dbase_get_record($dbf, $i);
									$QuantNeed = $row[2];
									$QuantPossible = 0;

//������� � ������ 									
$glDayWithoutSale = 7; 
$glDayByEnd = 20;
//-------------------
									
									//$move=0;
									$TableData = $DataModule->RestByName($row[1]);
									if (count($TableData)>0)
									{
										for ($j=1; $j<=count($TableData); $j++)
										{
											if (
												  (strtotime($TableData[$j][2])<strtotime('-'.$glDayWithoutSale.' day')) || //����_������� < (����������_���� -(����) ��_����������
													(
													  (strtotime($TableData[$j][3])-date("d.m.Y")<$glDayByEnd) && //�����_���������� -(����) ����������_���� < ��_ʲ���_���̲��
													  (strtotime($TableData[$j][3])-date("d.m.Y")>0) // �����_���������� -(����) ����������_���� > 0
													)
											   )	
											{
												if ($QuantNeed<$TableData[$j][1]) //���� ʲ��ʲ���_��_������� < ��������_�������_�_��������
												{
													$QuantPossible = $QuantNeed; //ʳ������_��_�����_���� = ʲ��ʲ���_��_�������
												}
												else
												{
													$QuantPossible = $TableData[$j][1];//ʳ������_��_�����_���� = ��������_�������_�_��������
												}
												echo $Table->TableRow(array($n, $TableData[$j][4], $TableData[$j][5], $TableData[$j][2], $TableData[$j][3], $TableData[$j][8], $QuantPossible, ""));
												$n++;
											}
										}
									  //$sale = $DataModule->SaleByName($row[1], date("d.m.Y", strtotime('-'.$glDayWithoutSale.' day')), date("d.m.Y"));
									}
								}
								echo $Table->TableEnd();
								dbase_close($dbf);
								unlink($_FILES['dbf']['tmp_name']);
								$DataModule->Disconnect();
								unset($DataModule);
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