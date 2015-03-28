<?php
// $reader = new MqlHistoryReader();
//  $reader->open('AUDUSD1.hst');

//  print_r($reader->get_headers());

// while($data = $reader->read()) {
// 	print_r($data);
// }

// $reader->close();

// .hst file format valid as of MT4 574 and later
class MqlHistoryReader
{
	var $fp;
	var $headers;
	
	function open($path) {
		$this->fp = fopen($path, 'r');
		$this->read_headers();
	}
	
	function read_headers() {
		// 4 bytes - version (int)
		// 64 bytes - copyright (char)
		// 12 bytes - symbol name (char)
		// 4 bytes - period (int)
		// 4 bytes - digits (int)
		// 4 bytes - time sign (int)
		// 4 bytes - last sync (int)
		// 52 bytes - unused
		
		$this->headers = unpack('Lversion/a64copyright/a12symbol/Lperiod/Ldigits/Ltimsign/Llastsync', 
			fread($this->fp, 4 + 64 + 12 + 4 + 4 + 4 + 4));
		
		// Unused 52 bytes
		fread($this->fp, 52);
	}
	
	function get_headers() {
		return $this->headers;
	}
	
	function read() {
		if(feof($this->fp)) return false;
		
		$data = fread($this->fp, 8);
		if(strlen($data) == 0) return false;
	
		$time = $this->_read_long($data);
		$data = unpack('dopen/dhigh/dlow/dclose', fread($this->fp, 8 * 4));
		$volume = $this->_read_long();
		$spread = $this->_read_int();
		$real_volume = $this->_read_long();
		$data['time'] = $time;
		$data['volume'] = $volume;
		$data['spread'] = $spread;
		$data['real_volume'] = $real_volume;
		
		return $data;
	}
	
	function close() {
		fclose($this->fp);
	}
	
	function _read_long($data = NULL) {
		if(!$data) $data = fread($this->fp, 8);
		$data = unpack('La/Lb', $data);
		return $data['a'] << 32 | $data['b'];
	}
	
	function _read_int() {
		$data = unpack('Lint', fread($this->fp, 4));
		return $data['int'];
	}
};
