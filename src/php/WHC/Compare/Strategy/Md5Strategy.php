<?php

class WHC_Compare_Strategy_Md5Strategy extends WHC_Compare_Strategy_CallbackStrategy {
	
	public function __construct() {
		parent::__construct('md5_file');
	}
	
}