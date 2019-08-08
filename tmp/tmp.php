<?php
test(0);

function test($idd)
{
    $id = hi($idd);
    if($id != false) {
        test($id);
    }
}

function hi($id)
{
    if ($id < 100) {
        $id += 1;
        sleep(1);
        tailf($id);
        if ($id > 5) {
            throw new PDOException('*******');
        }
        return $id;
    } else {
        return false;
    }
}

function tailf($msg = '', $desc = '', $filepath = '/var/www/html/demo/log/debug.log')
{
    $start = '[' . date('H:i:s') . '] ';
    if (is_array($msg) || is_object($msg)) {
        $out = str_replace(':', '=>', json_encode($msg, JSON_UNESCAPED_UNICODE));
        $out = str_replace('{', '[', $out);
        $out = str_replace('}', ']', $out);
        error_log($start . $desc . '--->' . $out . PHP_EOL, 3, $filepath);
    } else {
        error_log($start . $desc . '--->' . $msg . PHP_EOL, 3, $filepath);
    }
}