@ECHO OFF
REM Check php lines for proper formatting
REM Actual ruleset is used from joomla-cms 6.1 dev (2025.10.30)

CLS

ECHO PHP _phpcbf_help.bat
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

php --version
if errorlevel 1 (
	set path=%path%;%PhpExePath%
	ECHO *** php.exe assigned to path
    ECHO "%PhpExePath%

	php --version
	if errorlevel 1 (
		ECHO php exe not found in given PATH
		ECHO %path%
		GOTO :EOF
	)
)


ECHO.
ECHO ----------------------------------------------
ECHO command

REM ./libraries/vendor/bin/phpcs --extensions=php -p --standard=ruleset.xml
REM d:\Entwickl\2025\_gitHub\joomla-cms\ruleset.xml

REM "C:\Users\finnern\AppData\Roaming\Composer\vendor\bin\phpcbf.bat" --help
"C:\Users\finnern\AppData\Roaming\Composer\vendor\bin\phpcbf.bat" --help
GOTO :EOF

