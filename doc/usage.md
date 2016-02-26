---
currentMenu: usage
---

# Usage

## Command line commands
Currently phpct allow this commands:

Check tools commands: 

* __bom:__ Test if files have BOM (Byte Order Mark).
* __json:__ Test if JSON files are valide.

Others commands:

* __help:__ Displays help for a command.
* __list:__ Lists commands.

## Globals command line arguments
The unique argument is the path to scan.

## Globals command line options

### filename
__Syntaxe:__ `-f, --filename=FILENAME`

File name pattern to check (can use [regular expression](http://php.net/manual/en/reference.pcre.pattern.syntax.php)).

__Example:__ Find all ".md" and ".php": `--filename="/.(php|md)$/"`

### output
__Syntaxe:__ `-o, --output[=OUTPUT]`

Output result in a Junit XML file. Usable in [Jenkins CI](https://jenkins-ci.org/).
  
### filename-exclusion
__Syntaxe:__ `--filename-exclusion[=FILENAME-EXCLUSION]`

File name pattern extension (can use [regular expression](http://php.net/manual/en/reference.pcre.pattern.syntax.php)).

__Example:__ Exclude Test.php: `--filename-exclusion="/Test.php$/"`
     
### path-exclusion
__Syntaxe:__ `--path-exclusion[=PATH-EXCLUSION]`

Directory name pattern extension (can use [regular expression](http://php.net/manual/en/reference.pcre.pattern.syntax.php)).

__Example:__ Exclude tests directory: `--path-exclusion="/tests/"`

### noignore-vcs 
__Syntaxe:__ `--noignore-vcs`

By default the finder ignore VCS files and directories.
      
### Others options
* __-h, --help:__ Display help message.
* __-V, --version:__ Display this application version.
* __-n, --no-interaction:__ Do not ask any interactive question.
* __-v|vv|vvv, --verbose:__ Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug.
