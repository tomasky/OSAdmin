<?php
require('../include/init.inc.php');
if (Common::isPost ()) {
$workbook = new Spreadsheet_Excel_Writer();

// sending HTTP headers
$workbook->send('test.xls');

// Creating a worksheet
$worksheet =& $workbook->addWorksheet('My first worksheet');

// The actual data
$worksheet->write(0, 0, 'Name');
$worksheet->write(0, 1, 'Age');
$worksheet->write(1, 0, 'John Smith');
$worksheet->write(1, 1, 30);
$worksheet->write(2, 0, 'Johann Schmidt');
$worksheet->write(2, 1, 31);
$worksheet->write(3, 0, 'Juan Herrera');
$worksheet->write(3, 1, 32);

// Let's send the file
$workbook->close();
}

Template::assign("_POST", $_POST);
Template::display('sample/write_excel.tpl');
