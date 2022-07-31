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

function echoLine($index, $line)
{
    list($name, $pesarane, $dokhtarane, $rarity) = $line;
    $pesarane = $pesarane == 1 ? 'بله' : 'نه';
    $dokhtarane = $dokhtarane == 1 ? 'بله' : 'نه';
    echo "$index. نام: $name, پسرانه: $pesarane, دخترانه: $dokhtarane, میزان استفاده: $rarity\n";
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
