#!/usr/bin/env python3
"""
Stage 3 - verbose banner service (31337/tcp).

This is the "exploit": an over-chatty internal diagnostics service that
dumps its full status banner, including the flag fragment and the address
of the Stage 4 host, to anyone who connects. No authentication, no
interaction required - just connect (e.g. `nc <host> 31337`).
"""
import socket

HOST, PORT = "0.0.0.0", 31337

FLAG = "SHADOW{net_71f937}"

# Update this once the Stage 4 container actually exists on its own network
# segment - this is a placeholder host:port, same approach used for the
# Stage 6 credentials (fixed/documented value so this stage is independently
# testable before the rest of the box is built).
STAGE4_ADDRESS = "10.10.13.2:8080"

BANNER = f"""\
==================================================================
 shadow-nest-diag v0.9.3-internal -- staging node status
==================================================================
 node       : staging-03.shadowcollective.internal
 uptime     : 14d 02:51:09
 role       : relay / forwarding
 svc.ssh    : up   (port 22, non-default external mapping)
 svc.diag   : up   (this service, 31337/tcp)
 svc.proxy  : WARN - verbose mode left enabled in staging build
------------------------------------------------------------------
 debug.note : forwarding traffic to brokerage portal node at
              {STAGE4_ADDRESS}
 debug.flag : {FLAG}
==================================================================
 connection closing in 1s...
==================================================================
"""


def main():
    srv = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    srv.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    srv.bind((HOST, PORT))
    srv.listen(5)
    while True:
        conn, _ = srv.accept()
        try:
            conn.sendall(BANNER.encode())
        finally:
            conn.close()


if __name__ == "__main__":
    main()
