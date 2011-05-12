<?php

class WHC_Compare_Strategy_CallbackStrategy extends WHC_Compare_FileCompareStrategy {
	
	private $_callback = null;
	
	public function __construct($callback) {
		if (!is_callable($callback)) {
			throw new InvalidArgumentException("The callback is invalid");
		}
		
		$this->_callback = $callback;
	}
	
	public function isDifferent($file1, $file2) {
		$result1 = call_user_func($this->_callback, $file1);
		$result2 = call_user_func($this->_callback, $file2);
		
		return $result1 != $result2;
	}
	
}