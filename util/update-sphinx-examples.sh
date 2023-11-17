#!/bin/bash

set -e

help() {
    cat <<'!'
Usage: update-sphinx-examples.sh [OPTIONS]

    Run examples under docs/sphinx/examples and store their outputs in an
    output directory. The output directory is determined automatically and
    it depends on versions of PHP used and certain composer packages installed
    ('phpunit/phpunit', 'sebastian/exporter', ...).

Options:

  --php EXE

         Use specific PHP executable

  --tests

        Update example outputs used by doctests rather than the actual examples
        for documentation.

  -h|--help

         Display this help

Example:

        util/update-sphinx-examples.sh /usr/bin/php8.1

!
}

here="`dirname $0`";

top="$here/..";
abstop="`readlink -f $top`";

if ! options=$(getopt -o h -l php:,tests,help -- "$@"); then
  exit 1
fi

opt_php=
opt_tests=false

while [ $# -gt 0 ]; do
  case "$1" in
    --php)
      opt_php="$2"; shift 2;;
    --tests)
      opt_tests=true; shift;;
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

if ! [ $# -eq 0 ]; then
  help >&2;
  exit 1
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
