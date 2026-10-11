#!/bin/bash
set -e

# Fresh host keys per (re)creation, matching the golden-snapshot reset model.
rm -f /etc/ssh/ssh_host_*_key /etc/ssh/ssh_host_*_key.pub
ssh-keygen -A

# Internal-only decoy service (not published to the host - see compose.yaml)
python3 /opt/shadowtrace/internal_service.py &

# Verbose banner service (31337/tcp) - the actual intended target
python3 /opt/shadowtrace/banner_service.py &

exec /usr/sbin/sshd -D -e
