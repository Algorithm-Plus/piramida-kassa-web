<?php
include ("config.php");
$_SESSION["CurrentSection"]=3;
echo "<script language='JavaScript'>";
	echo "parent.frames['frametop'].location.reload();";
echo "</script>";

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

						echo "<font class='Midle'>".$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->FullName."</font>";
						echo "<BR>";
						echo "<table border=0>";

							echo "<TR valign='top'>";
							//��������
								echo "<TD>";

									$Form = "
									<form name='oborot' id='oborot' method=post target='_blank' action='tov_oborot.php'>
                  <p align=right>
										<font> ".$FormTovarItem2." </font><input type=text class='Edit 200' id='Shtrih' name='Shtrih' value=''><BR>
                    <font> ".$FormTovarItem15." </font><input type=text class='Edit 200' id='Ident' name='Ident' value=''>
                    </p>
										<font> ".$From." </font><input type=text class='Date' name='Date1' value='01.".date("m.Y")."'><font> ".$To."</font><input type=text class='Date' name='Date2' value='".date("d.m.Y")."'>
                    <BR>
										
										<input type=Submit class='ButtonPasive' value='".$FormTovarItem3."'><BR>
                    <BR>
                    <!--input type='checkbox' name='closed'> ".$FormTovarItem22." -->
                    <font> ".$FormCheckHeader2_9." </font><input type=text class='Edit 200' id='part' name='part' value=''><BR>
                    <input type=Button class='ButtonPasive' value='".$FormTovarItem21."' OnClick='SubmitByParts()'>
                    
									</form>";
									
                  $Form = $Form."
                  <form name='oborot2' id='oborot2' method=post target='_blank' action='tov_oborot_3.php'>
										<font> ".$FormTovarRadio4."</font><BR>
										<font> ".$From." </font><input type=text class='Date' name='Date1' value='01.".date("m.Y")."'><font> ".$To."</font><input type=text class='Date' name='Date2' value='".date("d.m.Y")."'>
										<BR>
										<input type=Submit class='ButtonPasive' value='".$FormTovarItem3."'>
									</form>
                  ";
                  
									$Div = "
										<div id='Seach'></div>
									";
									echo $Div;
									
									$TableInfo = new TableInfo($FormTovarItem1); //������� � ������ ������������� ���� � �������� $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Form);
									echo $TableInfo->Show();
                      
								echo "</TD>";
								//������ ������
								echo "<TD>";

									$Form = "
									<form name='zero' method=post target='_blank' action='tov_zero.php'>
										<p align='left'>
										<INPUT TYPE=radio NAME=Type VALUE='1'>".$FormTovarRadio1."<BR>
                    <INPUT TYPE=radio NAME=Type VALUE='0.9'>".$FormTovarRadio1." ".$FormTovarRadio6."<BR>
										<INPUT TYPE=radio NAME=Type VALUE='2'>".$FormTovarRadio2."<BR>
										<INPUT TYPE=radio NAME=Type VALUE='3'>".$FormTovarRadio3."<BR>
										<INPUT TYPE=radio NAME=Type VALUE='4'>".$FormTovarRadio4."<BR>
                    <INPUT TYPE=radio NAME=Type VALUE='5' CHECKED>".$FormTovarRadio5."<BR>
										</p>
										<input type=Submit class='ButtonPasive' value='".$FormTovarItem3."'>
									</form>";
									
									$TableInfo = new TableInfo($FormTovarItem4); //������� � ������ ������������� ���� � �������� $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Form);
									echo $TableInfo->Show();
                  
                  
                  
								echo "</TD>";
								
								echo "<TD>";
                //���������� �� ����������
									$Form = "
<form name='zero' method=post target='_blank' action='tov_def_zakaz_list.php'>
        <input type=Submit class='ButtonPasive' value='".$FormTovarItem25."'>
</form>
                  
<form name='zero' method=post target='_blank' action='tov_def_analiz_online.php'>
  <p align='left'>
  <input type='checkbox' name='ch01' id='ch01' checked>".$FormTovarZakaz01."<BR>
  <input type='checkbox' name='ch02' id='ch02' >".$FormTovarZakaz02."<BR>
  <input type='checkbox' name='ch03' id='ch03' >".$FormTovarZakaz03."<BR>
  <input type='checkbox' name='ch04' id='ch04' >".$FormTovarZakaz04."<BR>
  <input type='checkbox' name='ch05' id='ch05' >".$FormTovarZakaz05."<BR> 
  <input type=Submit class='ButtonPasive' value='".$FormTovarItem24."'>
  </p>
</form>


";
/*
$Form = $Form."
<form name='zero' method=post target='_blank' enctype='multipart/form-data' action='tov_def_analiz.php'>
  <BR>
  <input type='file' name='dbf' accept='dbf/*'>
  <input type=Submit class='ButtonPasive' value='".$FormTovarItem24."'>
</form>
									";
  */                  								
									$TableInfo = new TableInfo($FormTovarItem12.' '.$FormTovarDefecturaItem10); //������� � ������ ������������� ���� � �������� $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Form);
									echo $TableInfo->Show();
								echo "</TD>";
                echo "<TD>";
                //���������
                
                									$Form = "
<form name='zero' method=post target='_blank' action='tov_def_list.php'>
        <input type=Submit class='ButtonPasive' value='".$FormTovarItem6."'>
</form>

<form name='zero' method=post target='_blank' action='tov_def_edit.php'>
        <input type=Submit class='ButtonPasive' value='".$FormTovarItem7."'>
</form>
<form name='defcalc' method=post target='_blank' action='tov_def_calc.php'>
        <font> ".$From." </font><input type=text class='Date' name='Date1' value='01.".date("m.Y")."'><font> ".$To."</font><input type=text class='Date' name='Date2' value='".date("d.m.Y")."'>
        <BR>
        <font> ".$On."</font><input type=text class='Edit' SIZE=2 name='Period' value='7'><font> ".$FormTovarItem13."</font>
        <BR>
        <input type=Submit class='ButtonPasive' value='".$FormTovarItem12."' OnClick='defcalc.action=\"tov_def_calc.php\"; defcalc.submit();'>
        <BR><BR>
        <input type=Button class='ButtonPasive' value='".$FormTovarItem18."' OnClick='defcalc.action=\"tov_def_calc2.php\"; defcalc.submit();'>
</form>";
                  $TableInfo = new TableInfo($FormTovarItem5); //������� � ������ ������������� ���� � �������� $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Form);
									echo $TableInfo->Show();
                  
                  //echo "<BR>";
                //��� �� �������� ���������� �� ��� �������
                /*
                $Form = "
									<form name='prodagi' method=post target='_blank' action='tov_prodagi_all.php'>
										".$FormTovarSeriya2."<input type=text class='Date' name='Date1' value='".date("d.m.Y")."'><BR>
										<input type=Submit class='ButtonPasive' value='".$FormTovarItem3."'>
									</form>
									";
									
									$TableInfo = new TableInfo($FormTovarProdagi4); //������� � ������ ������������� ���� � �������� $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Form);
									echo $TableInfo->Show();
                */
								echo "</TD>";							
								
							echo "</TR>";
              
							echo "<TR>";
							
								echo "<TD valign='top'>";
                
                //��� �� �������� ����������
                $Form = "
                  <font> ".$FormTovarProdagi5." </font>
									<form name='prodagi' method=post target='_blank' action='tov_prodagi.php' style='margin:0;'>
										".$From."<input type=text class='Date' name='Date1' value='".date("d.m.Y")."'>".$To."<input type=text class='Date' name='Date2' value='".date("d.m.Y")."'><BR>
                    <input type='checkbox' name='with_rest'> ��������� ������� ���. ���.<br>
										<input type=Submit class='ButtonPasive' value='".$FormTovarItem3."'>
									</form>
                  <BR>
                  <font> ".$FormTovarProdagi4." </font>
                  <form name='prodagi' method=post target='_blank' action='tov_prodagi_all.php' style='margin:0;'>
										".$From."<input type=text class='Date' name='Date1' value='".date("d.m.Y")."'>".$To."<input type=text class='Date' name='Date2' value='".date("d.m.Y")."'><BR>
                    <font> ".$FormDiscountHeader1." </font><input type=text class='Edit 200' id='edrpou' name='edrpou' value=''><BR>
										<input type=Submit class='ButtonPasive' value='".$FormTovarItem3."'>
									</form>
									";
									$TableInfo = new TableInfo($FormTovarProdagi1); //������� � ������ ������������� ���� � �������� $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Form);
									echo $TableInfo->Show();
                                  
                echo "</TD>";  
								echo "<TD valign=top>";
                                
                //������� ���
									$Form = "
									<form name='seriya' method=post target='_blank' action='tov_seriya.php'>
										".$FormTovarSeriya2."<input type=text class='Date' name='Date1' value='".date("d.m.Y")."'><BR>
										<input type=Submit class='ButtonPasive' value='".$FormTovarItem3."'>
									</form>
                  ";
                  $TableInfo = new TableInfo($FormTovarSeriya1); //������� � ������ ������������� ���� � �������� $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Form);
									echo $TableInfo->Show();                  
                  echo "<BR>";                  
                  //������ ���������
									$Form = "
									<form name='seriya' method=post target='_blank' action='tov_reestr.php'>
										".$FormTovarSeriya2."<input type=text class='Date' name='Date1' value='".date("d.m.Y")."'><BR>
										<input type=Submit class='ButtonPasive' value='".$FormTovarItem3."'>
									</form>
                  ";
                  $TableInfo = new TableInfo($FormTovarRegistration1); //������� � ������ ������������� ���� � �������� $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Form);
									echo $TableInfo->Show();
                
                echo "</TD>";
								echo "<TD valign=top>";
                  //������ �� �����������
                  $Form = "
									<form name='zero' method=post target='_blank' action='tov_zero2.php'>
										<input type=text class='Edit 200' id='edrpou' name='edrpou' value='21642228'><BR>
										<input type=Submit class='ButtonPasive' value='".$FormTovarItem3."'>
									</form>";					
									$TableInfo = new TableInfo($FormTovarItem19); //������� � ������ ������������� ���� � �������� $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Form);
									echo $TableInfo->Show();
                  
                  //������� �������� ������
                    $Form = "
									<form name='toptovar' method=post target='_blank' action='tov_top.php'>
                    <p align='right' style='margin:0;'>
										".$From."<input type=text class='Date' name='Date1' value='01.".date("m.Y")."'>
                    ".$To."<input type=text class='Date' name='Date2' value='".date("d.m.Y")."'><BR>
                    ".$FormPreparatItem2."<input type=text class='Edit' SIZE=2 name='Count' value='20'>
                    </p>
										<input type=Submit class='ButtonPasive' value='".$FormTovarItem3."'>
									</form>
                  ";
                  $TableInfo = new TableInfo($FormPreparatItem6);
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Form);
									echo $TableInfo->Show();
                  
								echo "</TD>";
                
                echo "<TD valign='top'>";
								//³���� ������ (�����-�����)
                  
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
echo "<script language='JavaScript'>";
 echo "
    function SubmitByParts()
    {
      form=document.getElementById('oborot');
      form.action='tov_oborot_part.php';
      form.submit();
    }
  ";
echo "</script>";
echo "</BODY>";
?>