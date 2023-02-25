<?php
declare(strict_types=1);

require_once __DIR__ .'/clip_Test.php';

function clipBoard_Get_Test() {
    clipBoard_Set('ИЗ буфера');
    $result = clipBoard_Get();
    assert($result == 'ИЗ буфера');
}

function clipBoard_Set_Test() {
    $buff = 'в буфер!';
    clipBoard_Set($buff);
    assert(clipBoard_Get() == $buff);
}

function func_Name_Test() {
    // $code = '';
    //   assert(func_Name($code) == '');
    $code   = 'function z(){';
    $result = func_Name($code);
    echo_Old_2_New(__FUNCTION__, $code, $result);
    assert($result == 'z');
}

function func_Args_Test() {
    $code   = '$args';
    $result = func_Args($code);
    echo_Old_2_New(__FUNCTION__, $code, $result);

    $code   = 'string $args, array $arr';
    $result = func_Args($code);
    echo_Old_2_New(__FUNCTION__, $code, $result);
}

function test_From_ClipBoard_and_Put_Back_Test() {

    $code = 'function z($arg1, string $arg2){';
    clipBoard_Set($code);
    $result = test_From_ClipBoard_and_Put_Back() . PHP_EOL;
    assert ($result == "function z_Test(){\r\necho 'z_Test(\$arg1,\$arg2)';\r\n\$arg1 = '';\r\n\$arg2 = '';\r\n\r\n\$result = z(\$arg1,\$arg2);\r\n}\r\n\r\n");

    $code = 'function z(){';
    clipBoard_Set($code);
    $result = test_From_ClipBoard_and_Put_Back() . PHP_EOL;
    assert ($result == "function z_Test(){\r\necho 'z_Test()';\r\n\r\n\$result = z();\r\n}\r\n\r\n");

}

function test_String_Run_Test(){
    $name = '';
    $args = '';

    $result = test_String_Run($name, $args);
}

function args_Type_NO_Test(){
    $args = 'string $arg1';

    $result = args_Type_NO($args);
}

function string_Right_from_Separator_If_Test(){
    echo 'string_Right_from_Separator_If_Test($hiStack,$needle)';
    $hiStack = ';';
    $needle = ';';

    $result = string_Right_from_Separator_If($hiStack,$needle);
}

test_From_ClipBoard_and_Put_Back_Test();

// string_Right_from_Separator_If_Test();
//
// args_Type_NO_Test();
//
// func_Args_Test();
// func_Name_Test();
//
// clipBoard_Get_Test();
// clipBoard_Set_Test();
