@ECHO OFF
REM build_step.bat
REM like build_develop but does increase minor 'build' number 
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

set OptionFile=

if %1A==-dA (
	set OptionFile=-o options_version_tsk\build_develop.opt
)

if %1A==-sA (
	set OptionFile=-o options_version_tsk\build_step.opt
)

if %1A==-fA (
	set OptionFile=-o options_version_tsk\build_fix.opt
)

if %1A==-rA (
	set OptionFile=-o options_version_tsk\build_release.opt
)

if %1A==-mA (
	set OptionFile=-o options_version_tsk\build_major.opt
)

ECHO ----------------------------------------------
ECHO.

pushd  ..\..\buildExtension\src
REM dir /one /b *.tsk
ECHO Path: %cd% 

echo --- "%ExePath%php.exe" buildExtensionCmd.php -f ../../plg_remoteMarkdown/.buildPHP/build.tsk %1 %OptionFile%
"%ExePath%php.exe" buildExtensionCmd.php -f ../../plg_remoteMarkdown/.buildPHP/build.tsk %1 %OptionFile%
popd

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF

