<?php
header('Content-type: text/html; charset=windows-1251');
?>
<h1>Веб-модуль не до кінця налаштований</h1>
<p>
    Відсутній дані для підключення до бази данних
</p>
<p>
    Необхідно запустити скріпт ініціалізації <code><?php echo $_SERVER['DOCUMENT_ROOT'].'init.sh'?></code> (для Linux) або <code>'init.bat'</code> (для Windows)
</p>
