<?php

/**
 * @param string $baseName
 * @param string $folder
 * @return string
 */
function gentNameSpace(string $baseName, string $folder)
{
    return $baseName . '\Http\Controllers' . bs() . $folder;
}

/**
 * @return string
 */
function bs()
{
    $ds = DIRECTORY_SEPARATOR;
    return $ds;
}