<?php

function defaultVal($array, $key, $default) {
    return isset($array[$key]) ? $array[$key] : $default;
}

?>