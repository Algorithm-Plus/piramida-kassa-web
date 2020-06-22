<?php
class TableInfo
{
	var $Caption;
	var $Body;
	var $Border;

	function __construct($Caption)
	{
		$this->Caption = $Caption;
		$this->Body = "";
		$this->Border = 1;
	}

	function AddBody($Body)
	{
		$this->Body = $Body;
	}

	function ChangeBorder($Border)
	{
		$this->Border=$Border;
	}

	function Show()
	{
		$ReturnString = "
			<table  class='NoMargin' border=0>
				<TR>
					<TD  height=20px width=1px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-y;' background='template/".$_SESSION["Template"]."/img/tableinfo_border".$this->Border.".jpg'>
					</TD>
					<TD align='left' height=20px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-x;' background='template/".$_SESSION["Template"]."/img/tableinfo_header.jpg'>
						<font class='Midle'>&nbsp;&nbsp;".$this->Caption."&nbsp;&nbsp;<font>
					</TD>
					<TD  height=20px width=1px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-y;' background='template/".$_SESSION["Template"]."/img/tableinfo_border".$this->Border.".jpg'>
					</TD>
				</TR>
				<TR>
					<TD  height=20px width=1px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-y;' background='template/".$_SESSION["Template"]."/img/tableinfo_border".$this->Border.".jpg'>
					</TD>
					<TD align='center' height=20px style='margin: 0px; padding: 10px; BACKGROUND-REPEAT:  repeat-yx;' background='template/".$_SESSION["Template"]."/img/bkground1.jpg'>
						<font class='Midle'>".$this->Body."<font>
					</TD>
					<TD  height=20px width=1px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-y;' background='template/".$_SESSION["Template"]."/img/tableinfo_border".$this->Border.".jpg'>
					</TD>
				</TR>
				<TR>
					<TD  height=1px width=1px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-y;' background='template/".$_SESSION["Template"]."/img/tableinfo_border".$this->Border.".jpg'>
					</TD>
					<TD height=1px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-x;' background='template/".$_SESSION["Template"]."/img/tableinfo_border".$this->Border.".jpg'>
					</TD>
					<TD  height=1px width=1px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-y;' background='template/".$_SESSION["Template"]."/img/tableinfo_border".$this->Border.".jpg'>
					</TD>
				</TR>
			</table>";
		return $ReturnString;
	}
}
?>