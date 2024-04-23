#!/bin/bash

set -e

help() {
    cat <<'!'
Usage: packages-json.sh

    Produce json with packages for monorepo split job.
!
}

here="`dirname $0`";

top="$here/..";
abstop="`readlink -f $top`";

pushd $top > /dev/null

for composer_json in packages/*/composer.json; do
    directory=$(dirname "$composer_json");
    local_path=$(dirname "$composer_json" | xargs basename);
    jq -n --arg local_path  "$local_path" '$local_path' "$composer_json"
done | jq -craM -s '.'

popd > /dev/null
