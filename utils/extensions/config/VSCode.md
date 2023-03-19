# Extensions to Install on VS Code

First, on your project root:

-   Create a folder `.vscode`, if it does not exist.
-   Navigate into the folder `.vscode`.
-   Create a file `settings.json`, if it does not exist.

Now open `settings.json` and paste the below lines in the main JSON:

```
"editor.formatOnPaste": true
"editor.formatOnSave": true
```

## PHP Intelephense

This plugin will allow you to easily go to Definitions, Auto-Complete, View Functions Signatures...

To install it, open the Extensions tab, and install `bmewburn.vscode-intelephense-client`.

## PHP Unit

Pending to find a Plugin to run PHP Units

## Code Formatter

### Prettier

Prettier includes code formatting for most languages excluding PHP and SQL.

Open the Extensions tab, and search for: `esbenp.prettier-vscode`, and Install it.

To make `Prettier` your default `Code Formatter`, paste the below line in `.vscode/setings.json`.

```
"editor.defaultFormatter": "esbenp.prettier-vscode"
```

Then copy the file `.prettierrc` to the root of your project

### Others

Pending To find a Formatter for PHP, and MySQL (If Possible)
