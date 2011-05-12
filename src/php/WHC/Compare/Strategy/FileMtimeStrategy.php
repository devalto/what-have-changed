<?php

class WHC_Compare_Strategy_FileMtimeStrategy extends WHC_Compare_Strategy_CallbackStrategy {
	
	public function __construct() {
		parent::__construct('filemtime');
	}
	
}