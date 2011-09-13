<?php

require_once("Ellipsisizer.php");

$items = array("page-0" => array(),
			   "page-1" => array(),
			   "page-2" => array(),
			   "page-3" => array(),
			   "page-4" => array(),
			   "page-5" => array(),
			   "page-6" => array(),
			   "page-7" => array(),
			   "page-8" => array(),
			   "page-9" => array()
			   );

$items = Ellipsisizer::ellipsisize("page-5", $items, 5, false, "page-");
print "Non-JS Version: \n";
printItems($items);

$items = Ellipsisizer::ellipsisize("page-5", $items, 5, true, "page-");
print "JS Version: \n";
printItems($items);


function printItems ($items) {
	foreach ($items as $key => $item) {
		print "Key: \"" . $key . "\". Class: \"" . $item['itemAttributes']['class'] . "\"\n";
	}
}

?>
