<?php

abstract class WHC_Compare_FileCompareStrategy {
	
	abstract function isDifferent($file1, $file2);
	
}