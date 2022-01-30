<?php

namespace Albet\Asmvc\Core\Cli;

trait Cleanup
{
    /**
     * Delete files or directory in specific folder to cleanup.
     * @param string $path, array $diffed.
     */
    protected function cleanUp($path, $diffed)
    {
        $dirtho = [];
        foreach ($diffed as $diffed) {
            if (!str_contains($diffed, '.')) {
                $dirs = array_diff(scandir($path . $diffed . '/'), ['.', '..']);
                $dirtho[] = $diffed;
                foreach ($dirs as $dir) {
                    echo "Deleted: $dir\n";
                    unlink($path . $diffed . '/' . $dir);
                }
            } else {
                echo "Deleted: $diffed\n";
                unlink($path . $diffed);
            }
            if (!is_null($dirtho)) {
                foreach ($dirtho as $dir) {
                    echo "Deleted: $dir\n";
                    @rmdir($path . $dir);
                }
            }
        }
    }
}
