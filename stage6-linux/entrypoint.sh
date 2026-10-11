#!/bin/bash
# Regenerate host keys fresh each time the container is (re)created, so a
# `docker compose up --force-recreate` / reset behaves like the "golden
# snapshot" reset described in Section 7.3 of the design report.
set -e
rm -f /etc/ssh/ssh_host_*_key /etc/ssh/ssh_host_*_key.pub
ssh-keygen -A
exec /usr/sbin/sshd -D -e
