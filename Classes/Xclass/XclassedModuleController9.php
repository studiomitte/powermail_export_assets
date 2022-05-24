<?php

declare(strict_types=1);

namespace StudioMitte\PowermailExportAssets\Xclass;

use Alchemy\Zippy\Zippy;
use In2code\Powermail\Controller\ModuleController;
use In2code\Powermail\Utility\StringUtility;
use Psr\Http\Message\ResponseInterface;
use StudioMitte\PowermailExportAssets\ExportUtility;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;

class XclassedModuleController9 extends ModuleController
{

    /**
     * @return void
     * @throws InvalidQueryException
     * @noinspection PhpUnused
     */
    public function exportCsvAction(): ResponseInterface
    {
        $exportUtility = GeneralUtility::makeInstance(ExportUtility::class);
        $mails = $this->mailRepository->findAllInPid($this->id, $this->settings, $this->piVars);
        $this->view->assignMultiple(
            [
                'mails' => $mails,
                'fieldUids' => GeneralUtility::trimExplode(
                    ',',
                    StringUtility::conditionalVariable($this->piVars['export']['fields'], ''),
                    true
                )
            ]
        );

        if ($this->request->hasArgument('exportFiles') && $this->request->getArgument('exportFiles')) {
            $files = $exportUtility->collectAssets($mails);
        } else {
            $files = [];
        }

        $fileName = StringUtility::conditionalVariable($this->settings['export']['filenameCsv'], 'export.csv');

        if (empty($files)) {
            header('Content-Type: text/x-csv');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Pragma: no-cache');
            return $this->htmlResponse();
        }

        $zipFileName = str_replace('.csv', '.zip', $fileName);

        $randomizer = new Random();
        $exportDirectory = Environment::getVarPath() . '/transient/powermail-export/' . time() . '_' . $randomizer->generateRandomHexString(15) . '/';
        GeneralUtility::mkdir_deep($exportDirectory);
        $csvFilePath = $exportDirectory . $fileName;
        GeneralUtility::writeFile($csvFilePath, $this->view->render());

        $fullzipFilePath = $exportDirectory . $zipFileName;
        $zippy = Zippy::load();
        $zippy->create($fullzipFilePath, [
            'export.csv' => $csvFilePath,
            'media' => $exportUtility->persistMedia($files, $this->settings['uploadPath'], $exportDirectory)
        ], true);

        @ini_set('memory_limit', '512MB');
        return $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'application/zip')
            ->withHeader('Content-Length', (string)filesize($fullzipFilePath))
            ->withHeader('Content-Disposition', 'attachment; filename=' . $zipFileName)
            ->withBody($this->streamFactory->createStreamFromFile($fullzipFilePath));
    }

}
