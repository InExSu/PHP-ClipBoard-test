@echo off

@chcp 65001 > nul

for /f "tokens=2 delims==" %%I in ('wmic os get localdatetime /value') do set datetime=%%I

set year=%datetime:~0,4%
set month=%datetime:~4,2%
set day=%datetime:~6,2%
set hour=%datetime:~8,2%
set minute=%datetime:~10,2%
set second=%datetime:~12,2%

set datetime=%year%-%month%-%day% %hour%-%minute%-%second%

set file_path=%1
set folder_path=%2

set file_name=%~n1
set extension=%~x1

set new_file_name=%file_name%_%datetime%%extension%
set new_file_path=%folder_path%\%new_file_name%

copy "%file_path%" "%new_file_path%"
echo "Создана копия файла %file_name%"
echo "Новый файл: \"%new_file_path%\""
