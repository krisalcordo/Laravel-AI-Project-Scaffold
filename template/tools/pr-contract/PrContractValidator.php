<?php

namespace Tools\PrContract;

final class PrContractValidator
{
    /** @param array<string, mixed> $config */
    public function __construct(private readonly array $config)
    {
    }

    /**
     * @param list<string> $changedFiles
     * @param null|list<string> $archiveFiles
     * @return list<string>
     */
    public function validate(string $body, array $changedFiles, string $repoRoot, ?array $archiveFiles = null): array
    {
        $errors = [];
        $uiChanged = $this->matchesAny($changedFiles, $this->patterns('ui_paths'));
        $runtimeChanged = $this->matchesAny($changedFiles, $this->patterns('runtime_paths'));
        $frontendChanged = $this->matchesAny($changedFiles, $this->patterns('frontend_paths'));
        $migrationChanged = $this->matchesAny($changedFiles, $this->patterns('migration_paths'));

        foreach (['Summary', 'Preview', 'Test accounts', 'Browser validation', 'Test coverage', 'Validation'] as $heading) {
            $this->requireHeading($body, $heading, $errors);
        }
        $this->requireHeading($body, 'Remaining gaps', $errors, 3);

        $this->rejectPlaceholders($body, $errors);
        $this->validatePreview($body, $errors);
        $this->validateTestAccounts($body, $errors);

        if ($uiChanged) {
            $this->validateDesignContext($repoRoot, $errors);
            $this->validateImpeccableReview($body, $errors);
            $this->validateBrowserEvidence($body, $errors, $archiveFiles);
        } elseif (! str_contains($body, 'No browser-visible surface:')) {
            $errors[] = 'Non-UI PRs must state `No browser-visible surface: <why>`.';
        }

        if ($runtimeChanged) {
            $this->validateTestMappings($body, $repoRoot, $errors);
        }

        if ($frontendChanged && ! preg_match('/Frontend-only coverage\s*:/i', $body)) {
            $errors[] = 'Frontend changes must include a `Frontend-only coverage:` disclosure.';
        }

        if ($migrationChanged) {
            $this->validateSchemaCompanions($changedFiles, $errors);
        }

        return array_values(array_unique($errors));
    }

    /** @return list<string> */
    private function patterns(string $key): array
    {
        $value = $this->config[$key] ?? [];

        return is_array($value) ? array_values(array_filter($value, 'is_string')) : [];
    }

    /** @param list<string> $errors */
    private function rejectPlaceholders(string $body, array &$errors): void
    {
        foreach (['REPLACE_ME', ':PORT', '<branch-name>', '<why>', '__PROJECT_'] as $placeholder) {
            if (str_contains($body, $placeholder)) {
                $errors[] = "PR body still contains template placeholder: {$placeholder}.";
            }
        }
    }

    /** @param list<string> $errors */
    private function validatePreview(string $body, array &$errors): void
    {
        $section = $this->section($body, 'Preview');
        if ($section === null) {
            return;
        }

        if (preg_match('/No runnable preview:\s*\S+/i', $section)) {
            return;
        }

        if (! preg_match('#\[[^\]\r\n]+\]\(https?://[^)\s]+\)#i', $section)) {
            $errors[] = 'Preview must contain a clickable Markdown URL or `No runnable preview: <reason>`.';
        }
    }

    /** @param list<string> $errors */
    private function validateTestAccounts(string $body, array &$errors): void
    {
        $required = max(0, (int) ($this->config['required_test_account_count'] ?? 0));
        if ($required === 0) {
            return;
        }

        $section = $this->section($body, 'Test accounts');
        if ($section === null) {
            return;
        }

        if (preg_match('/No authentication required\./i', $section)) {
            return;
        }

        preg_match_all('/`[^`\s]+@[^`\s]+`[^\r\n]*`password`/i', $section, $matches);
        if (count($matches[0]) < $required) {
            $errors[] = "Test accounts must include at least {$required} safe seeded email/password row(s), or state `No authentication required.`";
        }
    }

    /** @param list<string> $errors */
    private function validateDesignContext(string $repoRoot, array &$errors): void
    {
        $files = $this->config['design_context_files'] ?? [];
        $placeholderPatterns = $this->config['design_context_placeholder_patterns'] ?? [];

        if (! is_array($files)) {
            return;
        }

        foreach ($files as $path) {
            if (! is_string($path)) {
                continue;
            }

            $fullPath = rtrim($repoRoot, '/').'/'.ltrim($path, '/');
            if (! is_file($fullPath)) {
                $errors[] = "UI changes require design context file: {$path}.";
                continue;
            }

            $contents = file_get_contents($fullPath) ?: '';
            if (strlen(trim($contents)) < 200) {
                $errors[] = "Design context file is too short to guide UI work: {$path}.";
            }

            if (is_array($placeholderPatterns)) {
                foreach ($placeholderPatterns as $pattern) {
                    if (is_string($pattern) && preg_match($pattern, $contents)) {
                        $errors[] = "Design context file still contains scaffold placeholders: {$path}.";
                        break;
                    }
                }
            }

            if (basename($path) === 'PRODUCT.md'
                && ! preg_match('/^##\s+Register\s*$\R\s*(?:product|brand)\s*$/mi', $contents)) {
                $errors[] = 'PRODUCT.md must declare an Impeccable register as bare `product` or `brand`.';
            }

            if (basename($path) === 'DESIGN.md') {
                if (! str_starts_with(ltrim($contents), '---')) {
                    $errors[] = 'DESIGN.md must begin with machine-readable YAML frontmatter.';
                }

                foreach (['Overview', 'Colors', 'Typography', 'Elevation', 'Components', "Do's and Don'ts"] as $section) {
                    if (! preg_match('/^##\s+(?:\d+\.\s+)?'.preg_quote($section, '/').'\b.*$/mi', $contents)) {
                        $errors[] = "DESIGN.md is missing required section: {$section}.";
                    }
                }
            }
        }
    }

    /** @param list<string> $errors */
    private function validateImpeccableReview(string $body, array &$errors): void
    {
        $this->requireHeading($body, 'Impeccable review', $errors);
        $checks = $this->config['impeccable_checks'] ?? [];

        if (! is_array($checks)) {
            return;
        }

        foreach ($checks as $label => $pattern) {
            if (is_string($pattern) && ! $this->hasCompletedChecklistLine($body, $pattern)) {
                $errors[] = "Impeccable review missing a completed status line: {$label}.";
            }
        }
    }

    /** @param list<string> $errors */
    private function validateBrowserEvidence(string $body, array &$errors, ?array $archiveFiles): void
    {
        if (str_contains($body, 'Not browser-validated:')) {
            if (! preg_match('/^###\s+Manual verification\s*$/mi', $body)
                || ! preg_match('#https?://#', $body)) {
                $errors[] = 'Browser disclaimers require `### Manual verification` with exact steps and a URL.';
            }

            return;
        }

        $this->requireHeading($body, 'Critical flows', $errors);
        $criticalFlows = $this->config['critical_flows'] ?? [];
        if (is_array($criticalFlows)) {
            foreach ($criticalFlows as $label => $pattern) {
                if (is_string($pattern) && ! $this->hasCompletedChecklistLine($body, $pattern)) {
                    $errors[] = "Critical flow missing a completed status line: {$label}.";
                }
            }
        }

        if (! preg_match('/^\s*[-*]?\s*(?:\*\*)?Console(?: errors)?(?:\*\*)?\s*:/mi', $body)) {
            $errors[] = 'Browser evidence must include a `Console:` result.';
        }

        if (! preg_match('/^\s*[-*]?\s*(?:\*\*)?Network(?: errors| requests)?(?:\*\*)?\s*:/mi', $body)) {
            $errors[] = 'Browser evidence must include a `Network:` result.';
        }

        preg_match_all('/!\[[^\]]+\]\(https:\/\/github\.com\/user-attachments\/assets\/[a-z0-9-]+\)/i', $body, $images);
        $inlineCount = count($images[0]);
        $minimum = max(0, (int) ($this->config['minimum_screenshots'] ?? 0));
        if ($inlineCount < $minimum) {
            $errors[] = "UI PRs require at least {$minimum} inline GitHub user-attachment screenshot(s).";
        }

        if (! preg_match('#`pr-assets/([A-Za-z0-9._/-]+/)`#', $body, $archiveMatch)) {
            $errors[] = 'Browser evidence must name its `pr-assets/<branch>/` archive path.';
        } elseif ($archiveFiles !== null) {
            $prefix = rtrim($archiveMatch[1], '/').'/';
            $archived = array_values(array_filter(
                $archiveFiles,
                static fn (string $file): bool => str_starts_with($file, $prefix)
                    && str_ends_with(strtolower($file), '.png'),
            ));

            if (count($archived) < $minimum) {
                $errors[] = "The archive `{$prefix}` must contain at least {$minimum} PNG screenshot(s).";
            }

            if (count($archived) > $inlineCount) {
                $errors[] = 'Every archived PNG must be represented by an inline screenshot in the PR body.';
            }
        }
    }

    /** @param list<string> $errors */
    private function validateTestMappings(string $body, string $repoRoot, array &$errors): void
    {
        preg_match_all('#tests/(?:Feature|Unit)/[A-Za-z0-9_./-]+Test\.php::test_[A-Za-z0-9_]+#', $body, $matches);
        $references = array_values(array_unique($matches[0]));

        if ($references === []) {
            $errors[] = 'Runtime changes require behavior-to-test mappings such as `tests/Feature/FooTest.php::test_behavior`.';

            return;
        }

        foreach ($references as $reference) {
            [$path, $method] = explode('::', $reference, 2);
            $fullPath = rtrim($repoRoot, '/').'/'.$path;
            if (! is_file($fullPath)) {
                $errors[] = "Mapped test file does not exist: {$path}.";
                continue;
            }

            $contents = file_get_contents($fullPath) ?: '';
            if (! preg_match('/function\s+'.preg_quote($method, '/').'\s*\(/', $contents)) {
                $errors[] = "Mapped test method does not exist: {$reference}.";
            }
        }
    }

    /** @param list<string> $errors */
    private function validateSchemaCompanions(array $changedFiles, array &$errors): void
    {
        $companions = $this->config['schema_companions'] ?? [];
        if (! is_array($companions)) {
            return;
        }

        foreach ($companions as $label => $pattern) {
            if (is_string($pattern) && ! $this->matchesAny($changedFiles, [$pattern])) {
                $errors[] = "Migration changes require {$label} change.";
            }
        }
    }

    /** @param list<string> $errors */
    private function requireHeading(string $body, string $heading, array &$errors, int $level = 2): void
    {
        if (! preg_match('/^'.str_repeat('#', $level).'\s+'.preg_quote($heading, '/').'\s*$/mi', $body)) {
            $errors[] = "Missing required heading: {$heading}.";
        }
    }

    private function section(string $body, string $heading): ?string
    {
        $pattern = '/^##\s+'.preg_quote($heading, '/').'\s*$\R(?<section>.*?)(?=^##\s+|\z)/ims';

        return preg_match($pattern, $body, $match) ? $match['section'] : null;
    }

    private function hasCompletedChecklistLine(string $body, string $pattern): bool
    {
        foreach (preg_split('/\R/', $body) ?: [] as $line) {
            if ((str_contains($line, '✅') || preg_match('/\[[xX]\]/', $line)) && preg_match($pattern, $line)) {
                return true;
            }
        }

        return false;
    }

    /** @param list<string> $files @param list<string> $patterns */
    private function matchesAny(array $files, array $patterns): bool
    {
        foreach ($files as $file) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $file)) {
                    return true;
                }
            }
        }

        return false;
    }
}
