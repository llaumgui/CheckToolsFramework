---
currentMenu: installation
---

# Installation

## Requirements
CheckToolsFramework requires PHP 5.5.9 or above on your machine.

## Composer
With [composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx), run:

```
$ php composer.phar global require llaumgui/CheckToolsFramework
```

You can also add CheckToolsFrameworks as a dependency for a project with `php composer.phar require llaumgui/CheckToolsFrameworks`.

Be aware that in order for CheckToolsFrameworks to be awesome it will install a good amount of other dependencies.
If you rather have it self-contained, use the **Phar** method just below.

## Phar
Alternatively, you can download [phpct.phar](http://llaumgui.github.io/CheckToolsFramework/phpct.phar):

```bash
$ curl -OS http://llaumgui.github.io/CheckToolsFramework/phpct.phar
```

**Please note that as Github is using a DDOS protection system, if using CURL fails, just manually download the phar file.**

If you want to run `phpct` instead of `php phpct.phar`, move it to `/usr/local/bin`:

```bash
$ chmod +x phpct.phar
$ sudo mv phpct.phar /usr/local/bin/phpct
```

Please note that you need to have the `phar` extension installed to use this method. It should be installed by default on most OSes.
