<?php
class ArrayFactory
{
	//var $FullName;

	function __construct()
	{

	}
	
	function TempTableCreate($FieldList)
	{
		//$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
		$LocalDataModule = new DataModule($_SESSION["AptekaTemp"][0]);
		$LocalDataModule->TempTableCreate("t_temp_".substr(session_id(),0,10), $FieldList);		
	}
	
	function TempTableDrop()
	{
		//$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
		$LocalDataModule = new DataModule($_SESSION["AptekaTemp"][0]);
		$LocalDataModule->TempTableDrop("t_temp_".substr(session_id(),0,10));
	}

	function ArrayAdd($FieldList, $Array)
	{
		//$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
		$LocalDataModule = new DataModule($_SESSION["AptekaTemp"][0]);
		$LocalDataModule->TempTableAddArray("t_temp_".substr(session_id(),0,10), $FieldList, $Array);
	}
	
	function GetArray($FieldList, $GroupField, $SumField, $OrderField=0)
	{
		//$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
		$LocalDataModule = new DataModule($_SESSION["AptekaTemp"][0]);
		$TableData = $LocalDataModule->TemtTableGetDate("t_temp_".substr(session_id(),0,10), $FieldList, $GroupField, $SumField, $OrderField);
		return $TableData;
	}
	
	function ShowArray($Array)
	{
		$Table = new Table();
		$Row = $Array[1];
		echo $Table->TableBegin(count($Row));
		for ($i=1; $i<=count($Array); $i++)
		{
			$Row = $Array[$i];
			echo $Table->TableRowFrom1($Row);
			
		}
		echo $Table->TableEnd();
	}
}
?>