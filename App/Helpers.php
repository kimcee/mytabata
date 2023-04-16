<?php

function dd($data)
{
    if (is_array($data) || is_object($data)) {
        echo '<pre>';
                print_r($data);
                echo '</pre>';
        exit;
    }

    var_dump($data);
    exit;
}