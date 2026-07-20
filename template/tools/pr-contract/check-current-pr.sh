#!/usr/bin/env bash

set -euo pipefail

command -v gh >/dev/null 2>&1 || { echo "gh is required" >&2; exit 2; }
command -v php >/dev/null 2>&1 || { echo "php is required" >&2; exit 2; }

repo_root=$(git rev-parse --show-toplevel)
pr=${1:-}
scratch=$(mktemp -d)
trap 'rm -rf "$scratch"' EXIT

if [ -n "$pr" ]; then
    gh pr view "$pr" --json body --jq .body > "$scratch/body.md"
    base_name=$(gh pr view "$pr" --json baseRefName --jq .baseRefName)
else
    gh pr view --json body --jq .body > "$scratch/body.md"
    base_name=$(gh pr view --json baseRefName --jq .baseRefName)
fi

git fetch origin "$base_name"
git diff --name-only "origin/$base_name...HEAD" > "$scratch/changed-files.txt"

if git fetch origin pr-assets:refs/remotes/origin/pr-assets 2>/dev/null; then
    git ls-tree -r --name-only origin/pr-assets > "$scratch/pr-assets-files.txt"
else
    : > "$scratch/pr-assets-files.txt"
fi

php "$repo_root/tools/pr-contract/validate.php" \
    --body-file="$scratch/body.md" \
    --changed-files="$scratch/changed-files.txt" \
    --archive-files="$scratch/pr-assets-files.txt" \
    --repo-root="$repo_root"

