<?php
/**
 * создать тест функции из буфера обмена и положить обратно в буфер
 */

// TODO сделай обработку аргументов со значениями по умолчанию и с &

declare(strict_types=1);

function test_From_ClipBoard_and_Put_Back(): string {

    // пробелов подряд не > 1
    $buff = preg_replace('/\s+/', ' ', clipBoard_Get());

    $name = 'function ' . func_Name($buff) . '_Test(){' . PHP_EOL;

    // массив аргументов
    preg_match('/\((.*?)\)/', $buff, $arr);

    $args = (count($arr) == 2) ?
        implode_If('',
                   $arr[1]) :
        implode_If('',
                   $arr);

    $run_ = test_String_Run($buff,
                            $args);

    $echo = "echo __FUNCTION__ . PHP_EOL;";

    $start = "\$start = microtime(true);";

    $args = func_Args(trim($args));

    $args = str_replace('&',
                        '',
                        $args);

    $time = "echo 'time passed = ' . (microtime(true) - \$start) . PHP_EOL;";

    /**
     * после функции, добавляю код вызова функции_теста
     */
    $call = func_Name($buff) . '_Test();';

    $code = $name .
            $echo . PHP_EOL .
            $start . PHP_EOL .
            $args . PHP_EOL .
            $run_ . PHP_EOL .
            $time . PHP_EOL .
            '}' . PHP_EOL .
            $call . PHP_EOL;

    clipBoard_Set($code);

    echo_Old_2_New(__FUNCTION__,
                   $buff,
                   $code);

    return $code;
}

function clipBoard_Get(): string {
    if (PHP_OS_FAMILY === 'Windows') {
        // works on windows 7 + (PowerShell v2 + )
        // is it -1 or -2 bytes? i think it was -2 on win7 and -1 on win10?
        return substr(shell_exec('powershell -sta "add-type -as System.Windows.Forms; [windows.forms.clipboard]::GetText()"'),
                      0,
                      -1);
    } elseif (PHP_OS_FAMILY === 'Linux') {
        // untested! but should work on X.org-based linux GUI's
        return substr(shell_exec('xclip -out -selection primary'),
                      0,
                      -1);
    } elseif (PHP_OS_FAMILY === 'Darwin') {
        // untested!
        return substr(shell_exec('pbpaste'),
                      0,
                      -1);
    } else {
        exit('running on unsupported OS: ' . PHP_OS_FAMILY . ' - only Windows, Linux, and MacOS supported.');
    }
}

function clipBoard_Set(string $new): bool {
    if (PHP_OS_FAMILY === 'Windows') {
        // works on windows 7 +
        $clip = popen('clip',
                      'wb');
    } elseif (PHP_OS_FAMILY === 'Linux') {
        // tested, works on ArchLinux
        $clip = popen('xclip -selection clipboard',
                      'wb');
    } elseif (PHP_OS_FAMILY === 'Darwin') {
        // untested!
        $clip = popen('pbcopy',
                      'wb');
    } else {
        exit('running on unsupported OS: ' . PHP_OS_FAMILY . ' - only Windows, Linux, and MacOS supported.');

    }
    $written = fwrite($clip,
                      $new);
    return (pclose($clip) === 0 && strlen($new) === $written);
}

/** в функции теста создать строку запуска  */
function test_String_Run(string $buff,
                         string $args): string {

    $args_Type_NO = args_Type_NO($args);

    /**
     * Если явно указан void, то без $result
     */
    $result = (strpos($buff, ": void"))
        ? ''
        : '$result = ';
    return $result .
           func_Name($buff) . '(' .
           $args_Type_NO . ');';
}

/**
 * избавиться от типов
 */
function args_Type_NO(string $args): string {

    $params = explode(',',
                      $args);
    $params = array_map('trim',
                        $params);

    $arr = [];

    foreach ($params as $pair) {
        $arr[] = arg1_Type_NO($pair);
    }

    return implode_If(',',
                      $arr);
}

/**
 * избавиться от типа в одном аргументе
 */
function arg1_Type_NO(string $pair): string {

    $arg = explode(' ',
                   $pair);

    return count($arg) == 2 ?
        $arg[1] :
        implode_If('',
                   $arg);
}

/**
 * вернуть имя функции
 */
function func_Name(string $code): string {

    $arr1 = explode('(',
                    $code);
    return trim(implode_if('',
                           explode('function ',
                                   $arr1[0])));
}

/** аргументы инициализировать */
function func_Args(string $args): string {

    $code = '';

    if ($args != '') {

        $arr_Comma = explode(',',
                             $args);

        foreach ($arr_Comma as $value) {

            $pair = explode(' ',
                            trim($value));

            $code .= argument_1($pair);
        }
    }
    return $code;
}

function argument_1(array $pair): string {

    switch (count($pair)) {
        case 1;
            $code = implode_If('',
                               $pair) . " = '';" . PHP_EOL;
            break;
        case 2:
            $code = argument_Init($pair);
            break;
        default:
            $code = '// в аргументе > 2 пробелов.' . PHP_EOL;
    }
    return $code;
}

/**
 * вернуть строку инициализации аргумента, в зависимости от типа
 */
function argument_Init(array $arg): string {

    $code = '';

    $type = $arg[0];
    $name = $arg[1];

    switch (strtolower($type)) {
        case 'array':
            $code .= "$name = [];" . PHP_EOL;
            break;
        case 'bool':
            $code .= "$name = false;" . PHP_EOL;
            break;
        case 'float':
            $code .= "$name = 0.0;" . PHP_EOL;
            break;
        case 'int':
            $code .= "$name = 0;" . PHP_EOL;
            break;
        case 'string':
            $code .= "$name = '';" . PHP_EOL;
            break;
        default:
            $code .= $name . PHP_EOL . '// ' . __FUNCTION__ . ' НЕ нашёл аргументов ...' . PHP_EOL;
    }
    return $code;
}

function echo_Old_2_New(string $s1,
                        string $s2,
                        string $s3) {
    echo $s1 . PHP_EOL .
         'из: ' . $s2 . PHP_EOL .
         'сделал: ' . PHP_EOL . $s3;
}

/**
 * implode, если массив
 */
function implode_If(string $separator, $array_Or_String): string {
    // если делать через тернарный, то будет Notice: Array to string conversion in
    if (is_array($array_Or_String))
        return implode_recursive($array_Or_String, $separator);
    else
        return (string)$array_Or_String;
}

/**
 * Recursively implodes an array with optional key inclusion
 *
 * Example of $include_keys output: key, value, key, value, key, value
 *
 * @access  public
 * @param array  $array        multi-dimensional array to recursively implode
 * @param string $glue         value that glues elements together
 * @param bool   $include_keys include keys before their values
 * @param bool   $trim_all     trim ALL whitespace from string
 * @return  string  imploded array
 */
function implode_recursive(array $array, $glue = ',', $include_keys = false, $trim_all = true): string {
    $glued_string = '';

    // Recursively iterates array and adds key/value to glued string
    array_walk_recursive($array, function ($value, $key) use ($glue, $include_keys, &$glued_string) {
        $include_keys and $glued_string .= $key . $glue;
        $glued_string .= $value . $glue;
    });

    // Removes last $glue from string
    strlen($glue) > 0 and $glued_string = substr($glued_string, 0, -strlen($glue));

    // Trim ALL whitespace
    $trim_all and $glued_string = preg_replace("/(\s)/ixsm", '', $glued_string);

    return (string)$glued_string;
}