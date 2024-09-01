<?php
function convert_date($date, $format = 'd-F-Y')
{
     return str_replace('-', ' ', jdate($format, strtotime($date)));
}