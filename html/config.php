<?php

include("class_tableinfo.php"); //клас інформаційних блоків
include("class_table.php"); //клас для роботи з таблицями
include("class_arrayfactory.php"); //клас для обробки масивів
include("class_datamodule.php"); //клас доступу до даних
include("class_apteka.php");

session_start();

$MainTitle = "Аптека-Плюс";
$AptekaList = array();
$AptIndex = 1;

//$_SESSION["AptekaTemp"] = array(new Apteka("Temp", "Temp №1", "tm", "localhost", "ds_temp", "1", "1", "вул. Зелена, 31", "430-480", 1.5, 0));

$glShiftBegin = '00:00:00';
$glShiftEnd   = '23:59:59';

putenv("TZ=Europe/Kiev");
ini_set("memory_limit","25M");

header('Content-type: text/html; charset=windows-1251');

if (file_exists($_SERVER['DOCUMENT_ROOT'].'config_local.php')){
    include('config_local.php');
} else {
    include('first_start.php');
    exit;
}

$_SESSION["Language"]='ukrainian';
include("language/".$_SESSION["Language"].".php");

$_SESSION["Template"]='dark';
//$_SESSION["Template"]='blackmarble';
$WidthPage=1000; //ширина основного блоку (для різних моніторів)
$FirsPageRefresh = 120; //Час оновлення першої сторінки в сек.
$ZoomGraph = 200; //Максимальна ширина графіка 

//Включити прийом СМС - 7021
//Блокувати СМС - 7020
$glEmailSMSReport = "38067ххххххх@sms.kyivstar.net"; //Номер тел. для отримання коротенького звіту по СМС
$glEmailFrom = "info@aplus.com";// З якого email відправляємо листи
ini_set("SMTP","outmail.voliacable.com"); //SMTP сервер

//GMAIL acount
$glGMAIL_user = "aplus@gmail.com";
$glGMAIL_password = "ххх";

$glDefecturaDepthMonth = 2; //К-ть місяців для детального аналізу
//$glDefectudaKoef = 1.5;
$glDefecturaMinCount = 0; //К-ть для видалення з замовлення

$glReportStrahPriceKoef=1;
$glReportStrahPriceKoefArr = array("СОС Сервис Украина"=>2, "СГ \"ТАС\""=>3);

include("ver.php");
?>