dotenv="$here/.env";

if [ ! -e "$dotenv" ]; then
  echo "error: file '$dotenv' does not exist!" >&2;
  echo "error: run 'php $here/initialize' to create it:" >&2;
  exit 1;
fi

. "$dotenv"

if [ -z "$DOCKER_BINARY" ]; then
  DOCKER_BINARY='docker';
fi
