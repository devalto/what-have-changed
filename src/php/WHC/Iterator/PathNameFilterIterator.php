<?php

class WHC_Iterator_PathNameFilterIterator extends RecursiveFilterIterator {

	private static $_filters = array();
	
	public static function setFilters($filters) {
		self::$_filters = $filters;
	}

	public function accept() {
		return !in_array(
			$this->current()->getFilename(),
			self::$_filters,
			true
		);
	}

}