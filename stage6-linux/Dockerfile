# Stage 6 - SC-LIN-01 : "Root of the Collective"
# Linux / System Security (capstone) - Operation ShadowTrace
#
# NOTE on implementation: the Assignment 01 design allowed either a light VM
# or a container per stage (Section 6, "Platform Type"). Stages 1/2/4 were
# implemented as containers rather than full VMs for the same reason this one
# is: faster to build/reset, identical learning outcome (SSH foothold ->
# credential reuse -> sudo/GTFOBins privilege escalation), and it still
# satisfies the isolation + reset requirements in Sections 6.4/7. This change
# should be logged in docs/design-changes.md ("VM -> container for Stage 6
# PrivEsc target; justification: faster golden-image rebuild, identical attack
# surface, no loss of learning outcome").

FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        openssh-server \
        sudo \
        gawk \
        nano \
        vim-tiny \
        iproute2 \
        ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# gawk is also reachable as /usr/bin/awk (the GTFOBins-listed binary name the
# sudo rule below targets).
RUN update-alternatives --install /usr/bin/awk awk /usr/bin/gawk 10

RUN mkdir -p /var/run/sshd

# ---------------------------------------------------------------------------
# Accounts
# ---------------------------------------------------------------------------
# analyst  - low-privilege foothold account. Its password is the credential
#            recovered from the Stage 5 malware-configuration blob.
# operative - second account whose password is disclosed only via a
#            world-readable sync/config file left on disk (the "readable
#            configuration file containing a second account's password" from
#            the Assignment 01 spec, Section 5.6).
RUN useradd -m -s /bin/bash analyst && \
    echo "analyst:N3st-Acc3ss_2026" | chpasswd && \
    useradd -m -s /bin/bash operative && \
    echo "operative:Gh0st_Proto-99!" | chpasswd

# Mid-stage flag: proves the player reached the box, sits in the low-priv
# user's home directory per the spec ("a mid-stage flag is also present in
# the low-privilege user's home directory to confirm progress").
RUN echo "SHADOW{midroot_4fbbf6}" > /home/analyst/flag_mid.txt && \
    chown analyst:analyst /home/analyst/flag_mid.txt && \
    chmod 440 /home/analyst/flag_mid.txt

# World-readable config file leaking the "operative" account's password.
# Deliberately placed outside any home directory, as a sync/backup artefact
# a careless sysadmin left behind - this is the enumeration target for
# LinPEAS / linux-smart-enumeration.
RUN mkdir -p /opt/shadowcollective/sync && \
    printf '# ShadowCollective internal account-sync cache\n# auto-generated - do not edit by hand\nservice_account=operative\nservice_password=Gh0st_Proto-99!\nlast_sync=2026-09-30T02:11:00Z\n' \
        > /opt/shadowcollective/sync/db_sync.conf && \
    chmod 644 /opt/shadowcollective/sync/db_sync.conf

# ---------------------------------------------------------------------------
# Privilege escalation vector: a single, deliberate sudo misconfiguration
# granting the "operative" account passwordless sudo on a GTFOBins-listed
# binary (awk). This is the ONLY sudo rule on the box.
#   Exploit: sudo awk 'BEGIN {system("/bin/sh")}'
# ---------------------------------------------------------------------------
RUN echo 'operative ALL=(root) NOPASSWD: /usr/bin/awk' > /etc/sudoers.d/90-operative-awk && \
    chmod 440 /etc/sudoers.d/90-operative-awk && \
    visudo -c

# Final flag - root-only readable, per spec.
RUN echo "SHADOW{root_c12f14}" > /root/flag.txt && \
    chmod 400 /root/flag.txt && \
    chown root:root /root/flag.txt

# ---------------------------------------------------------------------------
# Hardening (Section 6.4 security controls)
# ---------------------------------------------------------------------------
RUN sed -i \
        -e 's/^#\?PermitRootLogin.*/PermitRootLogin no/' \
        -e 's/^#\?PasswordAuthentication.*/PasswordAuthentication yes/' \
        -e 's/^#\?X11Forwarding.*/X11Forwarding no/' \
        -e 's/^#\?AllowTcpForwarding.*/AllowTcpForwarding no/' \
        -e 's/^#\?PermitTunnel.*/PermitTunnel no/' \
        /etc/ssh/sshd_config && \
    echo "AllowUsers analyst operative" >> /etc/ssh/sshd_config

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 22
ENTRYPOINT ["/entrypoint.sh"]
