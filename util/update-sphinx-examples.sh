#!/bin/bash

set -e

help() {
    cat <<'!'
Usage: update-sphinx-examples.sh [OPTIONS] [--all | EXAMPLE1 [ EXAMPLE2 ...]]

    Run examples under docs/sphinx/examples and store their outputs in an
    output directory. The default output directory is docs/sphinx/examples.
    If the --tests flag is specified, then the output directory changes to
    docs/sphinx/_output/{slug}, where {slug} depends on current version of PHP
    and other packages installed with composer ('phpunit/phpunit',
    'sebastian/exporter', ...).

Options:

  --all

        Update all examples, instead of specific ones.

  --php EXE

         Use specific PHP executable

  --tests

        Update example outputs used by doctests rather than the actual examples
        for documentation.

  -h|--help

         Display this help

Example:

        util/update-sphinx-examples.sh --php /usr/bin/php8.1 --all

!
}

here="`dirname $0`";

top="$here/..";
abstop="`readlink -f $top`";

if ! options=$(getopt -o h -l php:,tests,all,help -- "$@"); then
  exit 1
fi

opt_php=
opt_all=false
opt_tests=false

while [ $# -gt 0 ]; do
  case "$1" in
    --php)
      opt_php="$2"; shift 2;;
    --tests)
      opt_tests=true; shift;;
    --all)
      opt_all=true; shift;;
    -h|--help)
      help; exit 0;;
    --)
      shift; break;;
    -*)
      help >&2; echo ""; echo "$0: error: unrecognized option $1" >&2; exit 1;;
    *)
      break;;
  esac
done

if [ ! -z "$opt_php" ]; then
    if ([ -f "$opt_php" ] && [ -x "$opt_php" ]) || (which "$opt_php" >/dev/null 2>&1); then
        php_exe="$opt_php"
    elif which "php$opt_php" > /dev/null 2>&1; then
        php_exe="php$opt_php"
    else
        echo "" >&2
        echo "error: '$opt_php' is not a valid PHP interpreter" >&2
        echo "" >&2
        help >&2
        exit 1;
    fi
else
    php_exe='php'
fi

if ! $opt_all && [ $# -lt 1 ]; then
  echo "" >&2
  echo "error: you must either specify a list of examples or use '--all'" >&2
  echo "" >&2
  help >&2;
  exit 1
fi

if $opt_all; then
    pushd "$top/docs" > /dev/null
        readarray -d '' examples < <(find sphinx/examples -name "*Test.php" -print0)
    popd > /dev/null
else
    readarray -t examples < <(readlink -f "$@" | xargs -I{} realpath --relative-to="$top/docs" '{}')
fi

pushd $top/docs > /dev/null

outdir=`$php_exe sphinx/behat/get-output-path --relative`

test -e "$outdir" || mkdir -p "$outdir";

for t_php in `find sphinx/examples -name "*Test.php"`; do
    if $opt_tests; then
        t_base=`echo "$t_php"|sed -e 's:^sphinx/examples/\(.\+\)\.php$:\1:'`;
        t_stdout=`$php_exe sphinx/behat/get-output-path --relative ${t_base}.stdout`
    else
        t_base=`echo "$t_php"|sed -e 's:\.php$::'`
        t_stdout=${t_base}.stdout
    fi

    $php_exe ../vendor/bin/phpunit -c sphinx/examples/phpunit.xml $t_php \
      | sed -e "\\:^$abstop/packages/phpunit-\\w\\+:d" \
            -e "s:^$abstop/docs/sphinx/examples/\\([^/]*/\\)*\\(\\w\\+.php\\):\\2:" \
            -e "s|^\\(Configuration:\\s*\\)\\($abstop\\(/docs\\)\\?/\\)\\?sphinx/examples/phpunit\.xml|\\1phpunit.xml|" \
      | tee "$t_stdout";
done

popd > /dev/null
