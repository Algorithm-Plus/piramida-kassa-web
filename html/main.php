<?php
    include ("config.php");
?>
<HTML>
<HEAD>
<TITLE><?php echo $MainTitle ?></TITLE>
<meta http-equiv="Content-Type" content="text/html;charset=windows-1251">
</HEAD>
<FRAMESET rows='50, 1*' border=0>
        <FRAME src=frametop.php scrolling=no style='border-bottom: 0px solid #00952b' name=frametop noresize>
        <FRAMESET cols='150, 1*'>
                <FRAME src=frameleft.php style='border-right: 0px solid #00952b' name=frameleft scrolling=no noresize>
                <FRAME src=tovar.php style='border-right: 0px solid #00952b' name=workarea>
        </FRAMESET>
</FRAMESET>
</HTML>