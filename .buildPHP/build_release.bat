@ECHO OFF
REM build_release.bat
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

"%ExePath%php.exe" --version

ECHO ----------------------------------------------
ECHO.

pushd  ..\..\buildExtension\src
REM dir /one /b *.tsk
ECHO Path: %cd% 

echo --- "%ExePath%php.exe" buildExtensionCmd.php -f ../../remoteMarkdown/.buildPHP/build_release.tsk %1
"%ExePath%php.exe" buildExtensionCmd.php -f ../../remoteMarkdown/.buildPHP/build_release.tsk %1
popd

goto :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF
