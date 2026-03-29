@ECHO OFF
REM Check php lines for proper formatting
REM Actual ruleset is used from joomla-cms 6.1 dev (2025.10.30)

CLS

ECHO PHP _phpstan.bat
ECHO.
ECHO ----------------------------------------------
ECHO php path

REM Path for calling
REM set PhpExePath=e:\wamp64\bin\php\php8.4.5
set PhpExePath=e:\wamp64\bin\php\php8.3.19
REM ECHO PhpExePath: "%PhpExePath%"

if exist "%PhpExePath%/php.exe" (
    REM path known (WT)
    ECHO PhpExePath: "%PhpExePath%"
) else (
    REM Direct call
    ECHO PHP in path variable
    ECHO %path%
	@REM set PhpExePath=
)

REM ECHO "%PhpExePath%
REM "%PhpExePath%\php.exe" --version
REM ECHO PhpExePath: "%PhpExePath%"

ECHO.
ECHO ----------------------------------------------
ECHO php check

"%PhpExePath%\php.exe" --version
if errorlevel 1 (
	set path=%path%;%PhpExePath%
	ECHO php.exe assigned to path

	php --version
	if errorlevel 1 (
		ECHO php exe not found in given PATH
		ECHO %path%
		GOTO :EOF
	)
)

ECHO.
ECHO ----------------------------------------------
ECHO options

Set CmdArgs=

REM more options

@REM  path or file
set searchPath="d:\Entwickl\2026\_gitHub\mod_jx_std_icons"
if NOT %1A==A (
 	set searchPath=%1
)
ECHO  - 'search path "%searchPath%"'

REM ? standard ?
REM Call :AddNextArg -p

REM ruleset file
REM if NOT %2B==B (
REM  		Call :AddNextArg --standard=%2
REM ) else (
REM 	Call :AddNextArg --standard="d:\Entwickl\2025\_gitHub\joomla-cms\ruleset.xml"
REM )

ECHO.
ECHO ----------------------------------------------
ECHO command

REM ./libraries/vendor/bin/phpcs --extensions=php -p --standard=ruleset.xml
REM d:\Entwickl\2025\_gitHub\joomla-cms\ruleset.xml 

@REM ECHO "C:\Users\finnern\AppData\Roaming\Composer\vendor\bin\phpcs.bat" --extensions=php -p --standard=d:\Entwickl\2025\_gitHub\joomla-cms\ruleset.xml d:\Entwickl\2026\_gitHub\mod_jx_std_icons
@REM "C:\Users\finnern\AppData\Roaming\Composer\vendor\bin\phpcs.bat" --extensions=php -p --standard=d:\Entwickl\2025\_gitHub\joomla-cms\ruleset.xml d:\Entwickl\2026\_gitHub\mod_jx_std_icons
@REM "C:\Users\finnern\AppData\Roaming\Composer\vendor\bin\phpcs.bat" --help

ECHO "C:\Users\finnern\AppData\Roaming\Composer\vendor\bin\phpstan.bat" %CmdArgs% %searchPath%
"C:\Users\finnern\AppData\Roaming\Composer\vendor\bin\phpstan.bat" analyse %CmdArgs% %searchPath%

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
	Set NextArg=%*
	Set CmdArgs=%CmdArgs% %NextArg%
	ECHO  '%NextArg%'
GOTO :EOF

