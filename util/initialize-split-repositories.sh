#!/bin/bash

set -e

here="`dirname $0`";

top="$here/..";
abstop="`readlink -f $top`";
repobase="build/monorepo-split/repositories/phptailors";

if [ "`pwd`" != "$abstop" ]; then
  repobasedoc="${abstop}/${repobase}";
else
  repobasedoc="./${repobase}";
fi

usage() {
  cat >&2 <<!

USAGE:

  $0 [-f] [-d] [-a] [-v] [repo-base]

DESCRIPTION:

  Initialize bare git repositories under repo-base directory for packages split
  from "packages/*" subdirectories. If repo-base is missing, then the following
  default is used:

    $repobasedoc

  The created repositories may be next used as a target for the

    monorepo-builder split

  command.

OPTIONS:

  -f    force reinitialization (deletes existing repositories)
  -d    dry run, print commands that would be executed instead or running them
  -a    ansi output (do not use colors)
  -v    verbose mode
  -h    print help and exit

PARAMETERS:

  repo-base
        base directory under which package repositories will be created

!
}

warn() {
  echo -e "${brown}warning:${reset} $1" >&2;
}

error() {
  echo -e "${red}error${reset} $1" >&2;
}

info() {
  echo -e "${green}info:${reset} $1";
}

force=false;
dry=false;
ansi=false;
verbose=false;

while getopts "fdavh" option; do
  case $option in
    f)
      force=true;
      ;;
    d)
      dry=true;
      verbose=true;
      ;;
    a)
      ansi=true;
      ;;
    v)
      verbose=true;
      ;;
    h)
      usage;
      exit 0;
      ;;
    *)
      usage;
      exit 1;
      ;;
  esac
done

arg1="${@:$OPTIND:1}";
if [ ! -z "$arg1" ]; then
  repobase="$arg1";
fi

if $ansi; then
  red='';
  green='';
  brown='';
  reset='';
else
  red='\033[0;31m';
  green='\033[0;32m';
  brown='\033[0;33m';
  reset='\033[0m';
fi

if $verbose; then echo "pushd $abstop"; fi
pushd $abstop > /dev/null

for dir in packages/*; do
  if [ -d "$dir"  ] && [ -f "$dir/composer.json" ]; then
    package=`basename "$dir"`;
    repodir="${repobase}/${package}.git";

    if [ -e "$repodir" ]; then
      if $force; then
        warn "${repodir} exists, deleting it";
        if $verbose; then
cat -- <<!
rm -rf "${repodir}";
!
        fi
        $dry || rm -rf "${repodir}";
      else
        warn "${repodir} exists, skipping for ${package}";
        continue;
      fi
    fi

    info "creating bare repository for ${package} in ${repodir}";

    if $verbose; then
cat -- <<!
mkdir -p "$repodir";
git init --bare "$repodir" > /dev/null;
!
    fi
    if ! $dry; then
      mkdir -p "$repodir";
      git init --bare "$repodir" > /dev/null;
    fi
  fi
done

popd > /dev/null
if $verbose; then echo "popd"; fi
