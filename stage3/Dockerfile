# Stage 3 - SC-NET-01 : "Mapping the Nest"
# Networking / Enumeration - Operation ShadowTrace
#
# Implemented as a container rather than a full VM, same rationale logged for
# Stage 6 (faster golden-image rebuild, identical attack surface, no loss of
# learning outcome - log this in docs/design-changes.md alongside the Stage 6
# entry).

FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        openssh-server \
        python3 \
        netcat-openbsd \
        iproute2 \
        ca-certificates \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /var/run/sshd

# ---------------------------------------------------------------------------
# A real but unremarkable account - the host is only lightly hardened, so SSH
# itself is open, but there's nothing to find by logging in (no creds are
# handed out at this stage; the point of Stage 3 is the scan, not a shell).
# PasswordAuthentication is left on but no useful credentials exist yet in
# the player's hands at this point in the investigation.
# ---------------------------------------------------------------------------
RUN useradd -m -s /bin/bash svc-staging && \
    echo "svc-staging:$(openssl rand -base64 18)" | chpasswd

RUN sed -i \
        -e 's/^#\?PermitRootLogin.*/PermitRootLogin no/' \
        -e 's/^#\?X11Forwarding.*/X11Forwarding no/' \
        -e 's/^#\?AllowTcpForwarding.*/AllowTcpForwarding no/' \
        /etc/ssh/sshd_config

# Internal-only service: a minimal fake-MySQL banner responder. It is never
# published to the host (see compose.yaml), so it is NOT reachable from the
# participant/VPN side - it exists only to match the "internal-only service"
# item in the Assignment 01 spec and the Section 6.3 port table, which lists
# only 2201 and 31337 as exposed for this stage. Included for architectural
# completeness; players cannot see or use it.
COPY internal_service.py /opt/shadowtrace/internal_service.py

# Verbose banner service: on connect, dumps a chatty service banner
# containing the Stage 3 flag fragment and the Stage 4 host address.
COPY banner_service.py /opt/shadowtrace/banner_service.py

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh /opt/shadowtrace/*.py

# Internally sshd still listens on 22 and the banner service on 31337 - the
# "non-default high port" (2201) is only how it's published externally
# (see compose.yaml), exactly like Stage 6's 2222 -> 22 mapping.
EXPOSE 22 31337
ENTRYPOINT ["/entrypoint.sh"]
