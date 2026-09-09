@ECHO OFF
REM updateAll_fileHeaders.bat
REM
CLS

REM Path for calling
set ExePath=e:\wamp64\bin\php\php8.4.5\
REM ECHO ExePath: "%ExePath%"

if exist "%ExePath%php.exe" (
    REM path known (WT)
    ECHO ExePath: "%ExePath%"
) else (
    REM Direct call
    ECHO PHP in path variable
    set ExePath=
)

REM "%ExePath% --version
"%ExePath%php.exe" --version
ECHO.

ECHO ----------------------------------------------
ECHO.

REM more options 

set OptionFile=

REM if %1A==-dA (
REM 	set OptionFile=-o options_version_tsk\build_develop.opt
REM )


ECHO ----------------------------------------------
ECHO.

pushd  ..\..\buildExtension\src\
ECHO Path: %cd% 

echo --- "%ExePath%php.exe" doFileTasksCmd.php -f ../../plg_remoteMarkdown\.cleanPHP\updateAll_fileHeaders.tsk %OptionFile%
"%ExePath%php.exe" doFileTasksCmd.php -f ../../plg_remoteMarkdown\.cleanPHP\updateAll_fileHeaders.tsk %OptionFile%
popd

goto :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF
