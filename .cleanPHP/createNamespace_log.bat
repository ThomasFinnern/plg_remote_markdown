@ECHO OFF
REM createNamespace_log.bat
REM
CLS

ECHO PHP doFileTasksCmd.php createNamespace_log.tsk
ECHO.

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

ECHO ----------------------------------------------
ECHO.

REM more options 

set OptionFile=

ECHO ----------------------------------------------
ECHO.

pushd  ..\..\buildExtension\src
ECHO Path: %cd% 

echo --- "%ExePath%php.exe" doFileTasksCmd.php -f ../../plg_remoteMarkdown/.cleanPHP/createNamespace_log.tsk %1
"%ExePath%php.exe" doFileTasksCmd.php -f ../../plg_remoteMarkdown/.cleanPHP/createNamespace_log.tsk %1

popd

goto :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF
