<?php

include("class_tableinfo.php"); //���� ������������� �����
include("class_table.php"); //���� ��� ������ � ���������
include("class_arrayfactory.php"); //���� ��� ������� ������
include("class_datamodule.php"); //���� ������� �� �����
include("class_apteka.php");

session_start();

$MainTitle = "������-����";
$AptekaList = array();
$AptIndex = 1;

//$_SESSION["AptekaTemp"] = array(new Apteka("Temp", "Temp �1", "tm", "localhost", "ds_temp", "1", "1", "���. ������, 31", "430-480", 1.5, 0));

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
$WidthPage=1000; //������ ��������� ����� (��� ����� �������)
$FirsPageRefresh = 120; //��� ��������� ����� ������� � ���.
$ZoomGraph = 200; //����������� ������ ������� 

//�������� ������ ��� - 7021
//��������� ��� - 7020
$glEmailSMSReport = "38067�������@sms.kyivstar.net"; //����� ���. ��� ��������� ������������ ���� �� ���
$glEmailFrom = "info@aplus.com";// � ����� email ����������� �����
ini_set("SMTP","outmail.voliacable.com"); //SMTP ������

//GMAIL acount
$glGMAIL_user = "aplus@gmail.com";
$glGMAIL_password = "���";

$glDefecturaDepthMonth = 2; //�-�� ������ ��� ���������� ������
//$glDefectudaKoef = 1.5;
$glDefecturaMinCount = 0; //�-�� ��� ��������� � ����������

$glReportStrahPriceKoef=1;
$glReportStrahPriceKoefArr = array("��� ������ �������"=>2, "�� \"���\""=>3);

include("ver.php");
?>