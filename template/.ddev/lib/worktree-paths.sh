#!/usr/bin/env bash

# Resolve a bare worktree name across supported agent and manual layouts.
# Arguments: main checkout root, worktree name.
scaffold_worktree_dir() {
    local root="$1" name="$2" candidate repo_dir
    local -a found=()

    repo_dir=$(basename "$root")

    for candidate in "$root/.claude/worktrees/$name" "$root/.worktrees/$name"; do
        [ -d "$candidate" ] && found+=("$candidate")
    done

    candidate="$root/.worktrees/codex-app/$name/$repo_dir"
    [ -d "$candidate" ] && found+=("$candidate")

    case ${#found[@]} in
        1) printf '%s\n' "${found[0]}" ;;
        0)
            echo "No such worktree: $name" >&2
            echo "Looked under .claude/worktrees, .worktrees, and .worktrees/codex-app." >&2
            return 1
            ;;
        *)
            echo "Ambiguous worktree name '$name':" >&2
            printf '  %s\n' "${found[@]}" >&2
            return 1
            ;;
    esac
}

