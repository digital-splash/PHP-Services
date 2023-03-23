# Extensions to Install on VS Code

First, on your project root:

-   Create a folder `.vscode`, if it does not exist.
-   Navigate into the folder `.vscode`.
-   Create a file `settings.json`, if it does not exist.

Now open `settings.json` and paste the below lines in the main JSON:

```
"editor.formatOnPaste": false
"editor.formatOnSave": true
```

## PHP Intelephense

This plugin will allow you to easily go to Definitions, Auto-Complete, View Functions Signatures...

To install it, open the Extensions tab, and install `bmewburn.vscode-intelephense-client`.

## PHP Unit

Open the Extensions tab, and search for: `recca0120.vscode-phpunit`, and Install it.

Now open the file `.vscode/setings.json`, and paste the below lines.

```
"phpunit.php": "php",
"phpunit.phpunit": "vendor/bin/phpunit"
```

The below configuration is optional, but it can only be set on the user `settings.json` file. It is not supported per Workspace.
This configuration is set to `always` to Always show the PHP Unit results even when there is no Failures.

```
"phpunit.showAfterExecution": "always"
```

## PHP Debugger

Open the Extensions tab, and search for: `rxdebug.php-debug`, and Install it.

Now open the file `.vscode/setings.json`, and paste the below line.

```
"php.debug.executablePath": "{path_to_php_executable}"
```

**Still Under Construction!**

## Code Formatter

### Prettier

Prettier includes code formatting for most languages excluding PHP and SQL.

Open the Extensions tab, and search for: `esbenp.prettier-vscode`, and Install it.

To make `Prettier` your default `Code Formatter`, paste the below line in `.vscode/setings.json`.

```
"editor.defaultFormatter": "esbenp.prettier-vscode"
```

Then copy the file `.prettierrc` to the root of your project

### PHP

Open the Extensions tab, and search for: `kokororin.vscode-phpfmt`, and Install it.

Now open the file `.vscode/setings.json`, and paste the below lines.

```
  // Enable per-language
  "[php]": {
    "editor.defaultFormatter": "kokororin.vscode-phpfmt",
    "editor.formatOnSave": true,
    "editor.tabSize": 4
  },
  "phpfmt.passes": [
    "SpaceAroundControlStructures",
    "MergeElseIf",
    "IndentTernaryConditions",
    "SortUseNameSpace",
    "SpaceBetweenMethods",
    "PSR2LnAfterNamespace"
  ],
  "phpfmt.indent_with_space": 4,
  "phpfmt.detect_indent": false,
  "phpfmt.psr1": true,
  "editor.detectIndentation": false

```

### Others

Pending To find a Formatter for PHP, and MySQL (If Possible)
