<?php
	include_once "alomencoder.obfs.php";
	/**
	 * @method AlomEncoder::Obfuscator()
	 * @param string|callable $code (php code with tags and html code | php file name | an callable)
	 * @param array $settings = [] (obfuscate settings parameter)
	 */
	$settings = [
	  'rounds' => [
		'main' => [
		  'depth' => 1
		]
	  ],
	  'license' => [
		'title' => 'example',
		'author' => 'Dany'
	  ]
	];
	$fileList = glob('src/*.php');
	foreach($fileList as $filepath){
		if(is_file($filepath)){
			$filename = end(preg_split("#/#",$filepath));
			$obfs = AlomEncoder::obfuscator($filepath, $settings);
			file_put_contents(("../Helpers/" . $filename), $obfs); // save obfuscated script
		}   
	}
?>