<?php
	require "valveKV.php";

	$parser = new \ValveKV\ValveKV();
	//print_r($parser->parseFromString(file_get_contents("TestData/conditional.kv")));
	//print_r($parser->parseFromString(file_get_contents("TestData/quoteless.kv")));
	print_r($parser->parseFromString(file_get_contents("TestData/quoteless2.kv")));
	//print_r($parser->parseFromString(file_get_contents("TestData/dota_english.txt")));

	//var_dump($parser->parseFromString('"HELLO" { "A" " B " "C" "DE"}'));