<?php
class Table
{
	var $Border;
	var $RowCount;
	var $Rows;
	var $ColAlign; //Номер колонки, яку треба вирівняти
	var $TabRowDark; //Номер рядка який треба затемнити
	var $TabRowHeader; //Стиль заголовка для таблиці
	var $DarkRow; //у швидкому виводі
	var $HeaderRow;
	var $Width; //Ширина в %
	var $Cursor;
	var $JavaScript;

	function __construct()
	{
		$this->Border=1;
		$this->RowCount=0;
		$this->Rows = array();
		$this->ColAlign = array();
		$this->Width = 0;
		//$this->TabCelDark = array();
	}

	function AddRow($Row)
	{
		$this->RowCount = $this->RowCount+1;
		$this->Rows[$this->RowCount] = $Row;
		for ($ColNum=1; $ColNum<=Count($Row); $ColNum++)
		{
			$this->ColAlign[$ColNum] = 'Left';
		}
		//for ($RowNum=1; $RowNum<=$this->RowCount; $RowNum++)
		//{
			$this->TabRowDark[$this->RowCount] = "";
			$this->TabRowHeader[$this->RowCount] = "";
		//}
	}

	function ChangeBorder($Border)
	{
		$this->Border=$Border;
	}

	function ChangeAlign($ColNum, $Align)
	{
		$this->ColAlign[$ColNum] = $Align;
	}

	function ChangeLastRowDark() //Останній внесеней рядок затемняю
	{
		$this->TabRowDark[$this->RowCount] = "TabStyleDark";
	}

	function LastRowIsTableHeader()
	{
		$this->TabRowHeader[$this->RowCount] = "TabStyleHeader";
	}

	function Show()
	{
		$ReturnString = "<table class='Border".$this->Border."' border=1 id=menu name=menu>";
		for ($i=1; $i<=$this->RowCount; $i++)
		{
			$ReturnString = $ReturnString."<TR>";
				for ($j=0; $j<Count($this->Rows[$i]); $j++)
				{
					$ReturnString = $ReturnString."<TD class='".$this->TabRowDark[$i]." ".$this->TabRowHeader[$i]." TabStyle".$this->Border."' align='".$this->ColAlign[$j+1]."'><font class='Midle'>".$this->Rows[$i][$j]."</font></TD>";
				}
			$ReturnString = $ReturnString."</TR>";
		}
		$ReturnString = $ReturnString."</table>";
		return $ReturnString;
	}

	//online вивід таблиці, для прискореня
	function TableBegin($ColCount)
	{
		for ($i=1; $i<$ColCount+1; $i++)
		{
			$this->ColAlign[$i] = 'Left';
		}
		$style = "";
		if ($this->Width>0)
		{
			$style=$style." width:".$this->Width."%;";
		}
		$ReturnString = "<table class='Border".$this->Border."' border=1 style='".$style."' id=menu name=menu>";
		return $ReturnString;
	}

	function TableEnd()
	{
		$ReturnString = "</table>";
		return $ReturnString;
	}

	function TableRow($Row)
	{
		$ReturnString = "<TR ".$this->JavaScript.">";
    //print_r($Row);
		for ($i=0; $i<Count($Row); $i++)
		{
		$ReturnString = $ReturnString."<TD class='".$this->DarkRow." ".$this->HeaderRow." ".$this->Cursor."' align='".$this->ColAlign[$i+1]."'><font class='Midle'>".$Row[$i]."</font></TD>";
		}
		$ReturnString = $ReturnString."</TR>";
		$this->JavaScript = '';
		return $ReturnString;
	}
	
	function TableRowFrom1($Row)
	{
		$ReturnString = "<TR>";
		for ($i=1; $i<=Count($Row); $i++)
		{
		$ReturnString = $ReturnString."<TD class='".$this->DarkRow." ".$this->HeaderRow."' align='".$this->ColAlign[$i]."'><font class='Midle'>".$Row[$i]."</font></TD>";
		}
		$ReturnString = $ReturnString."</TR>";
		return $ReturnString;
	}

	function NextRowIsDark($Value)
	{
		if ($Value)
		{
			$this->DarkRow = 'TabStyleDark';
		}
		else
		{
			$this->DarkRow = '';
		}
	}

	function NextRowIsHeader($Value)
	{
		if ($Value)
		{
			$this->HeaderRow = 'TabStyleHeader';
		}
		else
		{
			$this->HeaderRow = '';
		}
	}

	function RowStart()
	{
		$ReturnString = "<TR>";
		return $ReturnString;
	}

	function RowEnd()
	{
		$ReturnString = "</TR>";
		return $ReturnString;
	}

	function AddCellColSpan($Value, $Count)
	{
		$ReturnString = "<TD class='".$this->DarkRow." ".$this->HeaderRow."' align='center' colspan='".$Count."'><font class='Midle'>".$Value."</font></TD>";
		return $ReturnString;
	}

	function AddCellRowSpan($Value, $Count)
	{
		$ReturnString = "<TD class='".$this->DarkRow." ".$this->HeaderRow."' align='center' rowspan='".$Count."'><font class='Midle'>".$Value."</font></TD>";
		return $ReturnString;
	}
	
	function AddCellSimple($Value, $Align)
	{
		$ReturnString = "<TD class='".$this->DarkRow." ".$this->HeaderRow."' align='".$Align."' ><font class='Midle'>".$Value."</font></TD>";
		return $ReturnString;
	}
	
	function AddJavaScript($Value)
	{
		$this->JavaScript = $Value;
	}
	
	
	function ShowMenu()
	{
		echo "<script type='text/javascript' src='hltable.js'></script>";
		echo "<script type='text/javascript'>highlightTableRows('menu','hoverRow','', false);</script>"; 
	}
}
?>