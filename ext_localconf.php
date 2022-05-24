<?php

$extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class);

try {
    $version = (int)$extensionConfiguration->get('powermail_export_assets', 'powermailVersion');
    if ($version === 8 || $version === 7) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\In2code\Powermail\Controller\ModuleController::class] = [
            'className' => \StudioMitte\PowermailExportAssets\Xclass\XclassedModuleController8::class,
        ];
    } else {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\In2code\Powermail\Controller\ModuleController::class] = [
            'className' => \StudioMitte\PowermailExportAssets\Xclass\XclassedModuleController9::class,
        ];
    }

} catch (\Exception $e) {

}
