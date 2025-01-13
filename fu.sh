#!/bin/bash

# Получаем директорию, где находится данный скрипт
directory_path="$(dirname "$0")"

# Отслеживаем изменения файлов в этой директории
while IFS= read -r file; do
    if [[ -e "$file" && ${file: -4} == ".php" ]]; then
        echo "Файл $file был изменен. Запускаю скрипт cPU.sh с параметром..."
        # Запускаем ваш скрипт с файлом в качестве параметра
        "$directory_path/cPU.sh" "$file"
    fi
done < <(fswatch -0 "$directory_path" | xargs -0 -n 1 echo)
