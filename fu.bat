@echo off

REM Получаем директорию, где находится данный скрипт
SET "directory_path=%~dp0"

REM Отслеживаем изменения .php файлов в этой директории
:loop
for /f "delims=" %%F in ('dir /b /s /a-d "%directory_path%\*.php"') do (
    REM Запускаем ваш скрипт с файлом в качестве параметра
    echo Файл %%F был изменен. Запускаю скрипт cPU.bat с параметром...
    "%directory_path%\cPU.bat" "%%F"
)
timeout /t 1 >nul
goto loop
