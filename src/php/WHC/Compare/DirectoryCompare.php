<?php

class WHC_Compare_DirectoryCompare {
	
	private $_dir;
	
	private $_files = array();
	
	private $_filters = array();
	
	/**
	 *
	 * @var WHC_Compare_FileCompareStrategy
	 */
	private $_file_comparer;
	
	public function __construct($directory, $file_comparer_strategy = null) {
		$this->_dir = $directory;
		$this->setFileComparerStrategy($file_comparer_strategy);
	}
	
	public function compare($cmp_directory) {
		$this->_files = $this->getFiles($this->_dir);
		$cmp_files = $this->getFiles($cmp_directory);
		
		$same = array_intersect($this->_files, $cmp_files);
		
		$modified = $this->getModified($this->_dir, $cmp_directory, $same);
		$added = array_diff($this->_files, $cmp_files);
		$deleted = array_diff($cmp_files, $this->_files);
		
		$results = array();
		foreach ($modified as $file) {
			$results[] = array(
				'file' => $file,
				'status' => 'M'
			);
		}
		
		foreach ($added as $file) {
			$results[] = array(
				'file' => $file,
				'status' => 'A'
			);
		}
		
		foreach ($deleted as $file) {
			$results[] = array(
				'file' => $file,
				'status' => 'D'
			);
		}
		
		return $results;
	}
	
	public function getModified($dir1, $dir2, $files) {
		$diff = array();
		
		foreach ($files as $file) {
			if ($this->_file_comparer->isDifferent($dir1.$file, $dir2.$file)) {
				$diff[] = $file;
			}
		}
		
		return $diff;
	}
	
	public function setFileComparerStrategy($strategy) {
		if (is_null($strategy)) {
			$strategy = new WHC_Compare_Strategy_Md5Strategy();
		}
		
		$this->_file_comparer = $strategy;
	}
	
	public function addFilter($name) {
		$this->_filters[] = $name;
	}
	
	/**
	 * Gets the path relative to a directory of all the files contained
	 *
	 * @param string $dir The name of a valid directory
	 * @return array List of all the files in the directory
	 */
	private function getFiles($dir) {
		if (!is_dir($dir)) {
			throw new InvalidArgumentException("\"$directory\" is not a directory");
		}
		
		$dir = realpath($dir);
		$files = array();
		
		$iterator = new RecursiveDirectoryIterator($dir);
		
		if (!empty($this->_filters)) {
			$iterator = new WHC_Iterator_PathNameFilterIterator($iterator);
			$iterator->setFilters($this->_filters);
		}
		
		$iterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);
		foreach ($iterator as $path) {
			if ($path->isFile()) {
				$files[] = str_replace($dir, "", $path->__toString());
			}
		}
		
		return $files;
	}
	
}