<?php
function convertToPersianNumber($str){
    $english = array('0','1','2','3','4','5','6','7','8','9');
    $persian = array('٠','١','٢','٣','٤','٥','٦','٧','٨','٩');

    $convertedStr = str_replace($english, $persian, $str);
    return $convertedStr;
}
