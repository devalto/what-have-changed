#!/usr/bin/env php
<?php
if (getenv('WHC')) {
	set_include_path(get_include_path() . PATH_SEPARATOR . realpath(getenv('WHC')));
}

require_once 'Zend/Loader/Autoloader.php';

$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Zend_');
$loader->registerNamespace('WHC_');

try {
	$opts = new Zend_Console_Getopt(array(
		'dont-exclude-svn' => 'Do not exclude SVN files from comparison by default'
	));
	$opts->parse();
	$args = $opts->getRemainingArgs();
} catch (Zend_Console_Getopt_Exception $e) {
	echo $e->getUsageMessage();
	exit(1);
}

$current = array_shift($args);
$old = array_shift($args);

$dir_compare = new WHC_Compare_DirectoryCompare($current);

if (!isset($opts->{'dont-exclude-svn'})) {
	$dir_compare->addFilter(".svn");
}

$results = $dir_compare->compare($old);

foreach ($results as $result) {
	echo $result['status'] . " " . $result['file'] . "\n";
}

exit(0);