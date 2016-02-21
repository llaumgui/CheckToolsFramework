---
currentMenu: builtin_test
---

# Built in tests
## Check BOM in files (bom)
```bash
$ phpct bom -h
Usage:
  bom [options] [--] <path> (<path>)...

Arguments:
  path                                           Path where to find files.
                                                              Can have mutliple values and can use string or regular expression.

Options:
  -f, --filename=FILENAME                        File name pattern to check (can use regular expression) [default: "*"]
  -o, --output[=OUTPUT]                          Junit XML ouput
      --filename-exclusion[=FILENAME-EXCLUSION]  File name pattern extension (can use regular expression)
      --path-exclusion[=PATH-EXCLUSION]          Directory name pattern extension (can use regular expression)
      --noignore-vcs                             By default the finder ignore VCS files and directories
  -h, --help                                     Display this help message
  -V, --version                                  Display this application version
  -n, --no-interaction                           Do not ask any interactive question
  -v|vv|vvv, --verbose                           Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
 For more informations about BOM, see http://en.wikipedia.org/wiki/Byte_order_mark.
 For more information about regular expression, see http://symfony.com/doc/current/components/finder.html.

 Example: Find al ".md" and ".php" in "vendor/Llaumgui", exculding "*Tests.php" and "tests" directory:
 php bin/phpct bom --filename="/.(php|md)$/" --filename-exclusion="/Test.php$/" --path-exclusion="/tests/" vendor/Llaumgui
```