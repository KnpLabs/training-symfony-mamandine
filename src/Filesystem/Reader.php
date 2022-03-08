<?php

namespace App\Filesystem;

use Exception;
use RuntimeException;

final class Reader
{
    /**
     * Returns the content of a file.
     *
     * @param string $path the path of the file
     *
     * @throws RuntimeException when the file is not found or cannot be read
     *
     * @return string the file content
     */
    public function getFileContent(string $path): string
    {
        if (false === $absolutePath = realpath($path)) {
            throw new RuntimeException(sprintf('Unable to determine the canonicalized absolute pathname for "%s".', $path));
        }

        try {
            $content = file_get_contents($absolutePath);
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        if (false === $content) {
            throw new RuntimeException(sprintf('Unable to read the content of "%s".', $absolutePath));
        }

        return $content;
    }
}
