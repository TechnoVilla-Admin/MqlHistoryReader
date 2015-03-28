# Mql History Reader
A PHP library to read mql history file (.hst)

# Usage

	include('MqlHistoryReader.php');
    $reader = new MqlHistoryReader();
    $reader->open('AUDUSD43200.hst');
    // Print headers
    print_r($reader->get_headers());
    // Print history
    while($data = $reader->read()) print_r($data);
    $reader->close();
