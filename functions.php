<?php

function getRarityLevelId($rarity)
{
    switch ($rarity) {
        case 'پرکاربرد': //ک ك
        case 'پركاربرد':
            return 0;
        case 'معمولی': // ی ي
        case 'معمولي':
            return 1;
        case 'بسیار نادر': // ی ي
        case 'بسيار نادر':
            return 2;
        default:
            die("Error: Unknown rarity: $rarity");
    }
}

function process($index, $line, $arr, $ceateJson)
{
    list($name, $pesarane, $dokhtarane, $rarity) = $line;
    if ($ceateJson && $rarity == 0) {
        if ($pesarane == 1 && $dokhtarane == 1)
            $arr['both'][] = $name;
        else if ($pesarane == 1)
            $arr['male'][] = $name;
        else if ($dokhtarane == 1)
            $arr['female'][] = $name;
    }

    $pesarane = $pesarane == 1 ? 'بله' : 'نه';
    $dokhtarane = $dokhtarane == 1 ? 'بله' : 'نه';
    echo "$index. نام: $name, پسرانه: $pesarane, دخترانه: $dokhtarane, میزان استفاده: $rarity\n";
    return $arr;
}

function flush_buffers()
{
    if (ob_get_length()) {
        ob_flush();
        flush();
        ob_end_flush();
    }
    ob_start();
}
