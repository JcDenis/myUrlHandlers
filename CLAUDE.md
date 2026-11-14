# myUrlHandlers

Notes for AI assistants working on the Dotclear plugin myUrlHandlers.

**Read first** the shared conventions of the author's Dotclear modules:
https://raw.githubusercontent.com/JcDenis/gh2da/master/CONVENTIONS.md
(if a web tool refuses the address, use `curl -s` on it from the shell).
The rules below take precedence for this module.

## Module

Change public URL of each Dotclear parts, like post, category, etc and some plugins having public parts.

Admin page. Nothing on the public side.

Required Dotclear and PHP versions: see `_define.php`.

## Structure

* `src/Backend.php`: admin side: menu entry, favorites, behaviors registration.
* `src/Install.php`: install and update, default settings.
* `src/Manage.php`: admin page of the module.
* `src/My.php`: module helpers (`My::id()`, settings, permissions).
* `src/MyUrlHandlers.php`: main class.
* `src/Prepend.php`: loaded on every request: autoload, URL handlers, permission types.
* `src/Uninstall.php`: cleaners for the plugin Uninstaller.
* `src/UrlDescriptor.php`: URL descriptor class.
* `src/UrlStack.php`: URL descriptor stack class.
* Behaviors used: `adminDashboardFavoritesV2`.

## Pitfalls

* None recorded yet: add here the traps met while working on the module.

## Testing

* Check the admin page, in English and French.
* CI browses the common admin and public pages; extra pages can be listed in `.github/dotclear-test.txt`.
