---
currentMenu: introduction
---

# Introduction

## What's CheckToolsFramework is ?
CheckToolsFramework is:

* A framework to build simples tests on multiple files, for exemple:
 * Be sur that all my files haven't BOM.
 * Validate all my XML files.
 * etc.
* A framework because you can add your own checker.
* Able to generate report in JUnit format, using [JunitXml](https://github.com/llaumgui/JunitXml).
* Compatible with [Travis](https://travis-ci.org/), because the `phpct` script exit with an error code in case of error.


## What's CheckToolsFramework isn't ?
CheckToolsFramework isn't:

* A tool to build units tests.
* A tool to build functionnals tests.
* A checkstyle tool.


## How I can use CheckToolsFramework
Currently you can use CheckToolsFramework in command line. Next you will use CheckToolsFramework in [post-commit hook](https://git-scm.com/book/it/v2/Customizing-Git-Git-Hooks).
