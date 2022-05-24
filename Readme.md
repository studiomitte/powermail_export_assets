# TYPO3 Extension `powermail_export_assets`

Enhance EXT:powermail to export attachments in the backend module by xclassing the original implementation.

## Requirements

- Composer
- TYPO3 9-11
- EXT:powermail 7.2-9.9

## Usage

Install with `composer req studiomitte/powermail-export-assets` and done.

### Using PHP 8

Currently the library to create the zip files is not fully compatible with PHP8. 
This can be fixed by appyling the patch file found in `Resources/Private/Patches/php8-alchemy-zippy.patch`.

```json
 "patches": {
      "alchemy/zippy": {
        "php8 notices": "patches/php8-alchemy-zippy.patch"
      }
    }
```


## Credits

This extension was created by Georg Ringer for [Studio Mitte, Linz](https://studiomitte.com) with ♥.

[Find more TYPO3 extensions we have developed](https://www.studiomitte.com/loesungen/typo3) that provide additional features for TYPO3 sites. 
