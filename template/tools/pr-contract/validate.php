#!/usr/bin/env php
<?php

use Tools\PrContract\PrContractValidator;

require __DIR__.'/PrContractValidator.php';

$options = getopt('', ['body-file:', 'changed-files:', 'archive-files::', 'repo-root::', 'config::']);
$bodyFile = $options['body-file'] ?? null;
$changedFilesPath = $options['changed-files'] ?? null;
$repoRoot = $options['repo-root'] ?? dirname(__DIR__, 2);
$archiveFilesPath = $options['archive-files'] ?? null;
$configPath = $options['config'] ?? __DIR__.'/config.php';

if (! is_string($bodyFile) || ! is_file($bodyFile)
    || ! is_string($changedFilesPath) || ! is_file($changedFilesPath)
    || ! is_string($configPath) || ! is_file($configPath)) {
    fwrite(STDERR, "Usage: php tools/pr-contract/validate.php --body-file=<path> --changed-files=<path> [--archive-files=<path>] [--repo-root=<path>] [--config=<path>]\n");
    exit(2);
}

$config = require $configPath;
if (! is_array($config)) {
    fwrite(STDERR, "PR contract config must return an array.\n");
    exit(2);
}

$body = file_get_contents($bodyFile) ?: '';
$changedFiles = array_values(array_filter(array_map('trim', file($changedFilesPath) ?: [])));
$archiveFiles = is_string($archiveFilesPath) && is_file($archiveFilesPath)
    ? array_values(array_filter(array_map('trim', file($archiveFilesPath) ?: [])))
    : null;

$errors = (new PrContractValidator($config))->validate($body, $changedFiles, (string) $repoRoot, $archiveFiles);

if ($errors !== []) {
    fwrite(STDERR, "PR contract failed:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, " - {$error}\n");
    }
    exit(1);
}

fwrite(STDOUT, "PR contract passed.\n");

