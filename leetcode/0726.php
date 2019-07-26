<?php
$x = 121;
echo isPalindrome($x);

function isPalindrome($x) {
    if(strrev($x) == $x) {
        return true;
    } else {
        return false;
    }
}