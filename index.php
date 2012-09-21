<?php
include 'DirectoryWalker.class.php';

if($argc < 2)
{
	echo 'Usage : php '.$argv[0].' /path/where/to/initiate/search filename'.PHP_EOL;
	exit;
}

$path = $argv[1];
$filename = $argv[2];
try
{
	$dw = new DirectoryWalker($path);
	$matches = $dw->add_rule(function($file)
	{
		return filesize($file) < 200 * 1024 * 1024;
	})->add_rule(function($file) use($filename)
	{
		return stripos($file, $filename) !== FALSE;
	})->find();


	print_r($matches);
}
catch(RuntimeException $e)
{
	echo $e->getMessage().PHP_EOL;
	exit;
}


?>
