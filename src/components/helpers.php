<?php

function unslug($text, $all_upper = false)
{
    $unslugged = str_replace(['-','_'], ' ', $text);

    return $all_upper ? ucwords($unslugged) : ucfirst($unslugged);
}
