ARG TLR_IMG
FROM ${TLR_IMG}
MAINTAINER Paweł Tomulik <ptomulik@meil.pw.edu.pl>

ARG TLR_USER='tailor'
ARG TLR_GROUP='tailor'
ARG TLR_UID=6789
ARG TLR_GID=6789
ARG TLR_HOME=/home/${TLR_USER}
ARG TLR_CODE=/code

ENV TLR_USER=$TLR_USER \
    TLR_GROUP=$TLR_GROUP \
    TLR_UID=$TLR_UID \
    TLR_GID=$TLR_GID \
    TLR_HOME=$TLR_HOME \
    TLR_CODE=$TLR_CODE

RUN getent group "$TLR_GID" > /dev/null && export TLR_GROUP=$(getent group "$TLR_GID" | cut -d: -f1); \
    getent passwd "$TLR_UID" > /dev/null && export TLR_USER=$(getent passwd "$TLR_UID" | cut -d: -f1 ); \
    if [ -f /etc/alpine-release ]; then \
      ( getent group "$TLR_GID" > /dev/null || addgroup -g "$TLR_GID" "$TLR_GROUP" ) && \
      ( getent passwd "$TLR_UID" > /dev/null || adduser -h "$TLR_HOME" -G "$TLR_GROUP" -u "$TLR_UID" -D "$TLR_USER" ); \
    else \
      ( getent group "$TLR_GID" > /dev/null || groupadd -g "$TLR_GID" "$TLR_GROUP" ) && \
      ( getent passwd "$TLR_UID" > /dev/null || useradd -m -d "$TLR_HOME" -g "$TLR_GID" --uid "$TLR_UID" "$TLR_USER" ); \
    fi; \
    mkdir -p $TLR_CODE && chown $TLR_UID:$TLR_GID $TLR_CODE

VOLUME $TLR_CODE

WORKDIR $TLR_CODE

# vim: set ft=dockerfile:
