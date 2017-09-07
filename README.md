Zikula InterCom Messages
====================

InterCom is a messages module for the Zikula Application Framework

### Branch < 3
Old module versions not supported.

 Current release 2.3.x
 This versions **require** Zikula Core 1.3.12-

### Branch 3
 Fully featured
 This version **requires** Zikula Core 1.5.0+
 The code is currently under development, but typically is in a functional
 state. Feel free to test and report issues. thank you.

### master
 Newest core features
 This version **requires** Zikula Core 2.0.0+
 The code is currently under development, but typically is in a functional
 state. Feel free to test and report issues. thank you.

#### Before you pull:

  1. uninstall and delete the module.
  2. delete your local repo and all the files.
  3. clone the repo into `/modules/zikula/intercom-module`

## Instalation

Your directory structure should look like so:

```
  /modules
      /zikula
          /intercom-module
            /Block
            /Command
            etc...
```

#### From zip

    1. Extract zip file to modules/zikula.
    2. Change directory that contains InterCom module to intercom-module
    3. Install module in Zikula extensions.

#### Composer

    module has no additional composer installation steps

## Upgrade

    Avoid any data loss by creating full backup before upgrade! 

#### InterCom version < 3.0.0
```
    UPGRADING from InterCom < 3
    You must add to `personal_config.php`:
    `$ZConfig['System']['prefix'] = 'pn';`
    (or whatever your table prefix is from your old installation)
    note that upgrading can take a long time and may require updating your `.htaccess` or `php.ini` file to increase 
    time limits and memory allowed.
```
#### InterCom version > 3.0.0

    Upgrade from version < 3 is handled using import facility.
    Upgrade from version > 3 is done normal way.

