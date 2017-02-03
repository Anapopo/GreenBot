@echo off
TITLE GreenBot
cd /d %~dp0

if exist bin\php\php.exe (
	set PHPRC=""
	set PHP_BINARY=bin\php\php.exe
) else (
	set PHP_BINARY=php
)

if exist GreenBot.phar (
	set GREENBOT_FILE=GreenBot.phar
) else (
	if exist src\GreenBot\GreenBot.php (
		set GREENBOT_FILE=src\GreenBot\GreenBot.php
	) else (
		echo "No GreenBot Installed."
		pause
		exit 1
	)
)

if exist bin\mintty.exe (
	start "" bin\mintty.exe -o Transparency=3 -o FontQuality=3 -o Font="Monaco" -o FontHeight=12 -o CursorType=0 -o CursorBlinks=1 -t "GreenBot" -w max %PHP_BINARY% %GREENBOT_FILE% %*
) else (
	%PHP_BINARY% -c bin\php %GREENBOT_FILE% %*
)
