<?php
if ($argc < 4) {
    die("Usage: batch.php <unluac> <Input> <Output>");
}

$IN_DIR =  $argv[2];
$OUT_DIR = $argv[3];

function iterDir($dir)
{
    global $OUT_DIR, $IN_DIR, $argv;
    if (is_dir($dir) && $dp = @opendir($dir)) {
        while (($f = @readdir($dp)) !== false) {
            if ($f == '.' || $f == '..') {
                continue;
            }
            $file = $dir . '/' . $f;
            if (is_dir($file)) {
                iterDir($file);
            } else {
                echo ($file . PHP_EOL);
                $out = $OUT_DIR . str_ireplace($IN_DIR, '', $dir) . '/';
                @mkdir($out, 0777, true);
                if (substr($f, -4) == 'luac') {
                    passthru('java -jar ' . escapeshellarg($argv[1]) . ' --rawstring ' . escapeshellarg($file) . ' > ' . escapeshellarg($out . basename($f, 'c')));
                } else {
                    copy($file, $out . $f);
                }
            }
        }
        @closedir($dp);
    }
}

iterDir($IN_DIR);
