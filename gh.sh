#!/bin/bash

# дать права на запуск
# chmod +x ./gh.sh

git pull

DATE=$(date "+%Y-%m-%d")

git add .
git commit -m "$DATE"
git push
