$logFilePath = ini_get('error_log'); // Получаем путь к файлу лога ошибок из php.ini
$maxLogFileSize = 1048576; // Максимальный размер файла в байтах (1 МБ в данном случае)

if ($logFilePath && file_exists($logFilePath) && filesize($logFilePath) > $maxLogFileSize) {
    // Если файл слишком большой, укорачиваем его до максимального размера
    $logFileContent = file_get_contents($logFilePath);
    $truncatedLogFileContent = substr($logFileContent, -$maxLogFileSize);
    file_put_contents($logFilePath, $truncatedLogFileContent);
}
