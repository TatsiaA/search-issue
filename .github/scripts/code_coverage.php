#!/usr/bin/php
<?php

defined('ROOT_PATH') or
define('ROOT_PATH', realpath(__DIR__ . '/../..'));

echo implode(' ', getData($argv[1] ?? null));

/**
 * Return coverage data [highLowerBound, actualCoverage]
 *
 * @author Dzianis Kotau <me@dzianiskotau.com>
 * @return array|false False if XML could not be parsed
 */
function getData(string $attribute = null): array|false
{
    if (($file = findConfig()) === false) {
        return false;
    }

    $xml = simplexml_load_file($file);
    if ($xml === false) {
        return false;
    }

    $report = $xml->coverage->report;
    if ($attribute === 'highLowerBound') {
        return [(int)$report->html['highLowerBound']];
    }

    $actualCoverage = findActualCoverage(realpath(ROOT_PATH . '/' . $report->text['outputFile']));
    if ($attribute === 'actualCoverage') {
        return [$actualCoverage];
    }

    return [(int)$report->html['highLowerBound'], $actualCoverage];
}

/**
 * Finds phpunit config file
 *
 * @author Dzianis Kotau <me@dzianiskotau.com>
 * @return string|false False if file does not exist. Otherwise, path to the file
 */
function findConfig(): string|false
{
    if (file_exists(ROOT_PATH . '/phpunit.xml')) {
        return ROOT_PATH . '/phpunit.xml';
    }

    if (file_exists(ROOT_PATH . '/phpunit.xml.dist')) {
        return ROOT_PATH . '/phpunit.xml.dist';
    }

    return false;
}

/**
 * Get actual code coverage in percentage
 *
 * @author Dzianis Kotau <me@dzianiskotau.com>
 * @param string $filePath
 * @return int
 */
function findActualCoverage(string $filePath): int
{
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lines = array_filter($lines, fn($line) => str_starts_with(trim($line), 'Lines'));
    $lines = array_values(array_map('trim', $lines));
    preg_match('/Lines:\s+(.*?)%/', $lines[0], $matches);

    return (int)$matches[1] ?? 0;
}
