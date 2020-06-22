<?php
include ("config.php");

echo "<BODY class='Default'>";
echo "<LINK REL='stylesheet' href='template/".$_SESSION["Template"]."/style.css' type='text/css'>";
echo "<table border=0>";
	echo "<TR>";
		echo "<TD width=200px>";
			echo "<img src='img/logo.gif'>";
		echo "</TD>";
		echo "<TD valign='bottom'>";
		if (!isset($_SESSION["CurrentSection"]))
		{
				$_SESSION["CurrentSection"] = 1;
		}
			//Загальний розділ 
			//Виводити розділ як активний (вибраний) чи пасивний (не вибраний)
			if ($_SESSION["CurrentSection"]==1)
			{
				$Corner='c';
				$Line='l';
				$Pasive=''; //для кольору пасивного шрифта
			}
			else
			{
				$Corner='cp';
				$Line='lp';
				$Pasive='Pasive';
			}
			echo "<table class='NoMargin'>";
				echo "<TR>";
					echo "<TD width=6px height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/".$Corner."_big_lt.jpg'>";	
					echo "</TD>";
					echo "<TD width='100px' height=20px align='center' style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-x;' background='template/".$_SESSION["Template"]."/img/".$Line."_big_t.jpg'>";
						echo "<a class='Midle ".$Pasive."' href='firstpage.php' target='workarea'>".$TopFrameItem1."</a>";
					echo "</TD>";
					echo "<TD width=6px height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/".$Corner."_big_rt.jpg'>";	
					echo "</TD>";
				echo "</TR>";
			echo "</table>";
		echo "</TD>";
		echo "<TD valign='bottom'>";
			//Каса
			if ($_SESSION["CurrentSection"]==2)
			{
				$Corner='c';
				$Line='l';
				$Pasive=''; //для кольору пасивного шрифта
			}
			else
			{
				$Corner='cp';
				$Line='lp';
				$Pasive='Pasive';
			}
			echo "<table class='NoMargin'>";
				echo "<TR>";
					echo "<TD width=6px height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/".$Corner."_big_lt.jpg'>";	
					echo "</TD>";
					echo "<TD width='100px' height=20px align='center' style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-x;' background='template/".$_SESSION["Template"]."/img/".$Line."_big_t.jpg'>";
						echo "<a class='Midle ".$Pasive."' href='kassa.php' target='workarea'>".$TopFrameItem2."</a>";
					echo "</TD>";
					echo "<TD width=6px height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/".$Corner."_big_rt.jpg'>";	
					echo "</TD>";
			echo "</table>";
			echo "<TD valign='bottom'>";
			//Товар
			if ($_SESSION["CurrentSection"]==3)
			{
				$Corner='c';
				$Line='l';
				$Pasive=''; //для кольору пасивного шрифта
			}
			else
			{
				$Corner='cp';
				$Line='lp';
				$Pasive='Pasive';
			}
			echo "<table class='NoMargin'>";
				echo "<TR>";
					echo "<TD width=6px height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/".$Corner."_big_lt.jpg'>";	
					echo "</TD>";
					echo "<TD width='100px' height=20px align='center' style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-x;' background='template/".$_SESSION["Template"]."/img/".$Line."_big_t.jpg'>";
						echo "<a class='Midle ".$Pasive."' href='tovar.php' target='workarea'>".$TopFrameItem3."</a>";
					echo "</TD>";
					echo "<TD width=6px height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/".$Corner."_big_rt.jpg'>";	
					echo "</TD>";
				echo "</TR>";
			echo "</table>";
		echo "</TD>";
		echo "<TD valign='bottom'>";
			//Чекі
			if ($_SESSION["CurrentSection"]==4)
			{
				$Corner='c';
				$Line='l';
				$Pasive=''; //для кольору пасивного шрифта
			}
			else
			{
				$Corner='cp';
				$Line='lp';
				$Pasive='Pasive';
			}
			echo "<table class='NoMargin'>";
				echo "<TR>";
					echo "<TD width=6px height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/".$Corner."_big_lt.jpg'>";	
					echo "</TD>";
					echo "<TD width='100px' height=20px align='center' style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-x;' background='template/".$_SESSION["Template"]."/img/".$Line."_big_t.jpg'>";
						echo "<a class='Midle ".$Pasive."' href='check.php' target='workarea'>".$TopFrameItem4."</a>";
					echo "</TD>";
					echo "<TD width=6px height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/".$Corner."_big_rt.jpg'>";	
					echo "</TD>";
				echo "</TR>";
			echo "</table>";
		echo "</TD>";
		echo "<TD valign='bottom'>";
			//Накладні
			if ($_SESSION["CurrentSection"]==5)
			{
				$Corner='c';
				$Line='l';
				$Pasive=''; //для кольору пасивного шрифта
			}
			else
			{
				$Corner='cp';
				$Line='lp';
				$Pasive='Pasive';
			}
			echo "<table class='NoMargin'>";
				echo "<TR>";
					echo "<TD width=6px height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/".$Corner."_big_lt.jpg'>";	
					echo "</TD>";
					echo "<TD width='100px' height=20px align='center' style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-x;' background='template/".$_SESSION["Template"]."/img/".$Line."_big_t.jpg'>";
						echo "<a class='Midle ".$Pasive."' href='nakladni.php' target='workarea'>".$TopFrameItem5."</a>";
					echo "</TD>";
					echo "<TD width=6px height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/".$Corner."_big_rt.jpg'>";	
					echo "</TD>";
				echo "</TR>";
			echo "</table>";
		echo "</TD>";
		echo "<TD valign='bottom'>";
			//Знижки
			if ($_SESSION["CurrentSection"]==8)
			{
				$Corner='c';
				$Line='l';
				$Pasive=''; //для кольору пасивного шрифта
			}
			else
			{
				$Corner='cp';
				$Line='lp';
				$Pasive='Pasive';
			}
			echo "<table class='NoMargin'>";
				echo "<TR>";
					echo "<TD width=6px height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/".$Corner."_big_lt.jpg'>";	
					echo "</TD>";
					echo "<TD width='100px' height=20px align='center' style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-x;' background='template/".$_SESSION["Template"]."/img/".$Line."_big_t.jpg'>";
						echo "<a class='Midle ".$Pasive."' href='discount.php' target='workarea'>".$TopFrameItem8."</a>";
					echo "</TD>";
					echo "<TD width=6px height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/".$Corner."_big_rt.jpg'>";	
					echo "</TD>";
				echo "</TR>";
			echo "</table>";
		echo "</TD>";
		echo "<TD valign='bottom'>";
			//Звіти
			if ($_SESSION["CurrentSection"]==6)
			{
				$Corner='c';
				$Line='l';
				$Pasive=''; //для кольору пасивного шрифта
			}
			else
			{
				$Corner='cp';
				$Line='lp';
				$Pasive='Pasive';
			}
			echo "<table class='NoMargin'>";
				echo "<TR>";
					echo "<TD width=6px height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/".$Corner."_big_lt.jpg'>";	
					echo "</TD>";
					echo "<TD width='100px' height=20px align='center' style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-x;' background='template/".$_SESSION["Template"]."/img/".$Line."_big_t.jpg'>";
						echo "<a class='Midle ".$Pasive."' href='reports.php' target='workarea'>".$TopFrameItem6."</a>";
					echo "</TD>";
					echo "<TD width=6px height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/".$Corner."_big_rt.jpg'>";	
					echo "</TD>";
				echo "</TR>";
			echo "</table>";
		echo "</TD>";
		echo "<TD valign='bottom'>";
			//Інформація
			if ($_SESSION["CurrentSection"]==7)
			{
				$Corner='c';
				$Line='l';
				$Pasive=''; //для кольору пасивного шрифта
			}
			else
			{
				$Corner='cp';
				$Line='lp';
				$Pasive='Pasive';
			}
			echo "<table class='NoMargin'>";
				echo "<TR>";
					echo "<TD width=6px height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/".$Corner."_big_lt.jpg'>";	
					echo "</TD>";
					echo "<TD width='100px' height=20px align='center' style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-x;' background='template/".$_SESSION["Template"]."/img/".$Line."_big_t.jpg'>";
						echo "<a class='Midle ".$Pasive."' href='about.php' target='workarea'>".$TopFrameItem7."</a>";
					echo "</TD>";
					echo "<TD width=6px height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/".$Corner."_big_rt.jpg'>";	
					echo "</TD>";
				echo "</TR>";
			echo "</table>";
		echo "</TD>";
		
		/*
		echo "<TD width=300px align='right' valign='bottom'>";
			echo "<font class='Midle'>".date("d.m.Y H:i")."</font>";
		echo "</TD>";
		*/
	echo "</TR>";
echo "</table>";
echo "</BODY>";
?>