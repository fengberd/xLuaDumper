<?php
if ($argc < 3) {
    die("Usage: diff.php <xLua DLL> <Original Lua DLL>");
}

chdir('x64\Debug');

// Lua 5.3
$ops = array(
    'MOVE',
    'LOADK',
    'LOADKX',
    'LOADBOOL',
    'LOADNIL52',
    'GETUPVAL',
    'GETTABUP',
    'GETTABLE',
    'SETTABUP',
    'SETUPVAL',
    'SETTABLE',
    'NEWTABLE',
    'SELF',
    'ADD',
    'SUB',
    'MUL',
    'MOD',
    'POW',
    'DIV',
    'IDIV',
    'BAND',
    'BOR',
    'BXOR',
    'SHL',
    'SHR',
    'UNM',
    'BNOT',
    'NOT',
    'LEN',
    'CONCAT',
    'JMP52',
    'EQ',
    'LT',
    'LE',
    'TEST',
    'TESTSET',
    'CALL',
    'TAILCALL',
    'RETURN',
    'FORLOOP',
    'FORPREP',
    'TFORCALL',
    'TFORLOOP52',
    'SETLIST52',
    'CLOSURE',
    'VARARG',
    'EXTRAARG'
);

function callDumper($dll)
{
    passthru('xLuaDumper.exe ' . $dll, $ret);
    if ($ret) {
        die('Failed to call dumper on ' . $dll);
    }
    return file_get_contents('out.luac');
}

function loadOps($lua)
{
    $ops = unpack('I', $lua)[1];
    if ($ops * 4 >= strlen($lua)) {
        die('Bad opcodes');
    }
    $result = array();
    for ($i = 1; $i <= $ops; $i++) {
        $result[] = ord($lua[$i * 4]) & 0b111111;
    }
    return $result;
}

// 45 为 xLua 兼容模式下的头部长度
$xlua = loadOps(substr(callDumper($argv[1]), 45));
$default = loadOps(substr(callDumper($argv[2]), 46));

$mapping = array();
for ($i = 0; $i < count($xlua); $i++) {
    $mapping[$xlua[$i]] = $default[$i];
}

ksort($mapping);

$out = '';
foreach ($mapping as $k => $v) {
    $out .= 'map[' . $k . '] = Op.' . $ops[$v] . ";\n";
}

for ($i = 0; $i < count($ops); $i++) {
    if (!isset($mapping[$i])) {
        $out .= '// map[' . $i . "] = Op.;\n";
    }
}

$out .= "// ----\n";
$counter = 0;
for ($i = 0; $i < count($ops); $i++) {
    if (!in_array($i, $mapping)) {
        $out .= '// Unknown: ' . $i . ' - ' . $ops[$i] . "\n";
        $counter++;
    }
}
$out .= "// Total: " . count($ops) . ', Unknown: ' . $counter . "\n";

echo ($out);
