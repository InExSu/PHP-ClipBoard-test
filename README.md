# PHP-ClipBoard-test

The php script creates a test from a method in the clipboard.

PHP-скрипт создает тест из метода в буфере обмена.

Приближение к TDD - сначала тест, затем код метода.

Когда придумал, что метод получает и что возвращает, то зафиксируй интерфейс метода.

    function method(array $arr, string $string): string {
    }

Cкопируй в буфер обмена, запусти в терминале ct.bat - в буфере будет код теста метода.

    function method_Test(){
        echo __FUNCTION__ . PHP_EOL;
        $start = microtime(true);
        $arr = [];
        $string = '';
        
        $result = method($arr,$string);
        echo 'time passed = ' . (microtime(true) - $start) . PHP_EOL;
    }
    method_Test();

Вставь из буфера обмена в файл методов тестов.

Осталось написать тело метода, закинуть тело в ChatGPT с просьбой написать тест, вставить код теста в тело метода теста.
