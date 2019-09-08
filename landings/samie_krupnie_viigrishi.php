<?php
require_once('../classes/simple_html_dom.php');


$html = file_get_html('http://onlajnkazinootzivy.com/samie_krupnie_viigrishi.html');
if ($html){ // Verify connection, return False if could not load the resource
$e = $html->find("a");
foreach ($e as $e_element){
    $old_href = $e_element->outertext;
    // Do your modification in here 
    //$e_element->href = affiliate($e_element->href); // for example I replace original link by the return of custom function named 'affiliate'
    $e_element->href = "https://takeuplay.com/?s=57&ref=wp_w25299p176_&url&popupAnchor=popup-reg"; //remove href
    $e_element->target .= "_blank"; // I added target _blank to open in new tab
    // end modification 
    $html = str_replace($old_href, $e_element->outertext, $html); // Update the href
}
}
  
$code = (string) $html;
print_r($code);