<?php

include('MqlHistoryReader.php');
header('content-type: text-plain');
$reader = new MqlHistoryReader();
$reader->open('AUDUSD43200.hst');
// Print headers
print_r($reader->get_headers());
// Print history
while($data = $reader->read()) print_r($data);
$reader->close();

?>
