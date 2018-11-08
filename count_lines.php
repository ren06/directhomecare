<?php

$path = dirname(__FILE__) . '/protected';

$dir = new DirectoryIterator($path);

$totalLineCount = 0;

foreach ($dir as $fileinfo) {

    if ($fileinfo->isDir() && !$fileinfo->isDot()) {

        $fileName = $fileinfo->getFilename();

        if ($fileName == 'certificates' || $fileName == 'data' || $fileName == 'extensions' || $fileName == 'migrations' || $fileName == 'runtime' || $fileName == 'upload') {

            //skip this folder
            continue;
        } else {

            //recursively get all folders
            echo '<br>';
            $dirName = $fileinfo->getPathname();

            $path = realpath($dirName);

            $totalLineCount = 0;

            $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);

            foreach ($objects as $name => $object) {

                echo "$name\n";

                if (!$object->isDir()) //SplFileInfo
                    $lineCount = lineCount($name);
                $totalLineCount += $lineCount;

                echo '<br>';
            }
        }
    }
    echo '<br>';
}

echo $totalLineCount;

function lineCount($file) {

    $linecount = 0;
    $handle = fopen($file, "r");
    while (!feof($handle)) {
        if (fgets($handle) !== false) {
            $linecount++;
        }
    }
    fclose($handle);
    return $linecount;
}
?>