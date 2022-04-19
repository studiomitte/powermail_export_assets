<?php

declare(strict_types=1);

namespace StudioMitte\PowermailExportAssets;

use In2code\Powermail\Domain\Model\Answer;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

class ExportUtility {


    public function persistMedia(array $media, $path, string $exportPath)
    {
        $uploadPath = $this->getUploadPath($path);
        if (!$uploadPath) {
            die('no upload path found');
        }
        $exportPath = $exportPath . 'assets/';
        GeneralUtility::mkdir_deep($exportPath);
        foreach ($media as $file) {
            if (is_file($uploadPath . $file)) {
                copy($uploadPath . $file, $exportPath . $file);
            }
        }
        return $exportPath;
    }

    protected function getUploadPath(string $path)
    {
        // using FAL although plain path/file is supplied to trigger all hooks and signals
        $storageRepository = GeneralUtility::makeInstance(StorageRepository::class);
        $allStorages = $storageRepository->findAll();
        foreach ($allStorages as $thisStorage) {
            try {
                $thisStorageBasePath = $thisStorage->getConfiguration()['basePath'];
                if (strpos($path, $thisStorageBasePath) === 0) {
                    $subPath = substr($path, strlen($thisStorageBasePath));
                    if ($thisStorage->hasFolder($subPath)) {
                        $folder = $thisStorage->getFolder($subPath);
                        return $folder->getPublicUrl();
                    }
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
                die;
            }
        }
        if (is_dir(GeneralUtility::getFileAbsFileName($path))) {
            return Environment::getPublicPath() . '/' . $path;
        }
        die('Damn');
    }

    /**
     * @param QueryResult $mails
     * @return array
     */
    public function collectAssets($mails): array
    {
        $files = [];
        foreach ($mails as $mail) {
            foreach ($mail->getAnswers() as $answer) {
                /** @var Answer $answer */
                if ($answer->getField() && $answer->getField()->getType() === 'file') {
                    foreach ($answer->getValue() as $k => $v) {
                        $files[$mail->getUid() .'_' . $answer->getUid() . '_' . $k] = $v;
                    }
                }
            }
        }
        return $files;
    }
}
