#!/usr/bin/env bash

load_scaffold_settings() {
    local root="$1"
    local settings="$root/.project-scaffold"

    if [ ! -f "$settings" ]; then
        echo "Missing $settings" >&2
        return 1
    fi

    # This file is project-owned and committed. Do not place secrets in it.
    # shellcheck disable=SC1090
    source "$settings"

    : "${PROJECT_SLUG:?PROJECT_SLUG is required in .project-scaffold}"
    : "${PREVIEW_HOST_PREFIX:?PREVIEW_HOST_PREFIX is required in .project-scaffold}"
    : "${PREVIEW_SLOT_COUNT:=10}"
    : "${PREVIEW_BASE_PORT:=8200}"
    : "${PREVIEW_HEALTH_PATH:=/}"
    : "${SCAFFOLD_SMOKE_TEST_FILTER:=ExampleTest}"
    : "${SHARE_PREVIEW_UPLOADS:=0}"
}

