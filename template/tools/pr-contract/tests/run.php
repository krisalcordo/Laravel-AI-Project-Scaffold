#!/usr/bin/env php
<?php

use Tools\PrContract\PrContractValidator;

require dirname(__DIR__).'/PrContractValidator.php';

$config = require dirname(__DIR__).'/config.php';
$validator = new PrContractValidator($config);
$root = sys_get_temp_dir().'/project-scaffold-contract-'.bin2hex(random_bytes(6));
mkdir($root.'/tests/Feature', 0777, true);

file_put_contents($root.'/tests/Feature/ExampleTest.php', <<<'PHP'
<?php

final class ExampleTest
{
    public function test_example(): void
    {
    }
}
PHP);

$baseBody = <<<'MD'
## Summary

Validated behavior.

## Preview

[Open preview](http://sample-preview.ddev.site:8200)

## Test accounts

| Scenario | Email | Password |
| --- | --- | --- |
| Demo | `demo@sample.test` | `password` |

## Browser validation

No browser-visible surface: documentation-only change.

## Test coverage

| Behavior | Test |
| --- | --- |
| Example | `tests/Feature/ExampleTest.php::test_example` |

### Remaining gaps

None.

## Validation

- [x] Complete
MD;

$uiBody = <<<'MD'
## Summary

Validated UI behavior.

## Preview

[Open preview](http://sample-preview.ddev.site:8200)

## Test accounts

| Scenario | Email | Password |
| --- | --- | --- |
| Demo | `demo@sample.test` | `password` |

## Browser validation

- Console: no unexpected errors
- Network: no unexpected failures

![Changed state](https://github.com/user-attachments/assets/a1)
![Persisted state](https://github.com/user-attachments/assets/b2)
![Downstream state](https://github.com/user-attachments/assets/c3)

Durable archive: `pr-assets/feature-branch/`

## Critical flows

- [x] Authentication / sign-in
- [x] Primary dashboard
- [x] Core update round-trip

## Test coverage

| Behavior | Test |
| --- | --- |
| Example | `tests/Feature/ExampleTest.php::test_example` |

Frontend-only coverage: Browser review covers client interaction.

### Remaining gaps

None.

## Validation

- [x] Complete
MD;

$archiveFiles = [
    'feature-branch/01-changed.png',
    'feature-branch/02-persisted.png',
    'feature-branch/03-downstream.png',
];

$tests = [
    'documentation-only PR passes' => static function () use ($validator, $baseBody, $root): void {
        assertSame([], $validator->validate($baseBody, ['docs/guide.md'], $root, []));
    },
    'complete UI evidence passes' => static function () use ($validator, $uiBody, $root, $archiveFiles): void {
        assertSame([], $validator->validate($uiBody, ['resources/js/Pages/Home.tsx'], $root, $archiveFiles));
    },
    'UI evidence omissions fail' => static function () use ($validator, $baseBody, $root): void {
        $errors = $validator->validate($baseBody, ['resources/js/Pages/Home.tsx'], $root, []);
        assertContains('Browser evidence must include a `Console:` result.', $errors);
        assertContains('Frontend changes must include a `Frontend-only coverage:` disclosure.', $errors);
    },
    'runtime mapping must exist' => static function () use ($validator, $baseBody, $root): void {
        $body = str_replace('tests/Feature/ExampleTest.php::test_example', 'tests/Feature/MissingTest.php::test_missing', $baseBody);
        $errors = $validator->validate($body, ['app/Services/Example.php'], $root, []);
        assertContains('Mapped test file does not exist: tests/Feature/MissingTest.php.', $errors);
    },
    'migration companions are enforced' => static function () use ($validator, $baseBody, $root): void {
        $errors = $validator->validate($baseBody, ['database/migrations/2026_01_01_000000_example.php'], $root, []);
        assertContains('Migration changes require a factory change.', $errors);
        assertContains('Migration changes require a demo seeder change.', $errors);
    },
    'manual browser instructions are accepted' => static function () use ($validator, $baseBody, $root): void {
        $browser = "Not browser-validated: browser service unavailable.\n\n### Manual verification\n\nOpen http://sample-preview.ddev.site:8200 and complete the flow.";
        $body = preg_replace(
            '/No browser-visible surface: documentation-only change\./',
            $browser,
            $baseBody,
        );
        $body .= "\n\nFrontend-only coverage: Manual browser verification required.\n";
        assertSame([], $validator->validate($body, ['resources/js/Pages/Home.tsx'], $root, []));
    },
];

$failed = 0;
foreach ($tests as $name => $test) {
    try {
        $test();
        fwrite(STDOUT, "PASS: {$name}\n");
    } catch (Throwable $error) {
        $failed++;
        fwrite(STDERR, "FAIL: {$name}\n  {$error->getMessage()}\n");
    }
}

removeTree($root);

if ($failed > 0) {
    exit(1);
}

fwrite(STDOUT, "All PR contract tests passed.\n");

/** @param list<mixed> $haystack */
function assertContains(mixed $needle, array $haystack): void
{
    if (! in_array($needle, $haystack, true)) {
        throw new RuntimeException('Expected value not found: '.var_export($needle, true).' in '.var_export($haystack, true));
    }
}

function assertSame(mixed $expected, mixed $actual): void
{
    if ($expected !== $actual) {
        throw new RuntimeException('Expected '.var_export($expected, true).', got '.var_export($actual, true));
    }
}

function removeTree(string $path): void
{
    if (! is_dir($path)) {
        return;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST,
    );
    foreach ($iterator as $item) {
        $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
    }
    rmdir($path);
}

