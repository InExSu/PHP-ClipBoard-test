# PHP-ClipBoard-test

The php script creates a test from a method in the clipboard.


PHP-скрипт создает тест из метода в буфере обмена.

Приближение к TDD - сначала тест, затем код метода.

Когда придумал, что метод получает и что возвращает, то зафиксируй интерфейс метода.

function method(array $arr, string $string): string {
}

Выдели код метода, скопируй в буфер обмена, запусти батник - в буфере будет код теста метода.

function method_Test() {
    echo 'method_Test($arr,$string)';
    $arr    = [];
    $string = '';

    $result = method($arr, $string);
}

