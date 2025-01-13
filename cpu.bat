@echo off
setlocal

for /f "tokens=1-3 delims=-" %%a in ('date /t') do set "DATE=%%c-%%b-%%a"

git pull
git add .
git commit -m "%DATE%"
git push

if "%~1"=="" (
    echo Ошибка: не передано имя файла. Выход
    exit /b 1
)

if not exist "%~1" (
    echo Ошибка: файл "%~1" не существует.
    exit /b 1
)

for %%F in ("%~1") do set "filename_no_extension=%%~nF"

set "folder_name=drn_BackUps"

if not exist "%folder_name%" (
    mkdir "%folder_name%"
    echo Папка создана: %folder_name%
)

call file_Copy_Date.bat "%filename_no_extension%.drn" "%folder_name%"
