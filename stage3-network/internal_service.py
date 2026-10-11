#!/usr/bin/env python3
"""
Internal-only service for Stage 3.

Listens on 0.0.0.0:3306 *inside the container only*. compose.yaml does not
publish this port to the host, so it is unreachable from the
participant/VPN side - it only exists so the box matches the Assignment 01
architecture (an "internal-only service" alongside SSH and the banner
service). It sends a generic MySQL-looking greeting and nothing else; there
is no flag, credential, or vulnerability here. It is not part of the
intended solve path.
"""
import socket
import struct

HOST, PORT = "0.0.0.0", 3306


def fake_mysql_greeting() -> bytes:
    # Loosely mimics a MySQL initial handshake packet - just enough to look
    # real in a banner-grab, with no further protocol support.
    payload = (
        b"\x0a"  # protocol version
        b"8.0.34-0ubuntu0.22.04.1\x00"
        b"\x01\x00\x00\x00"
        b"deadbeef\x00"
    )
    header = struct.pack("<I", len(payload))[:3] + b"\x00"
    return header + payload


def main():
    srv = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    srv.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    srv.bind((HOST, PORT))
    srv.listen(5)
    while True:
        conn, _ = srv.accept()
        try:
            conn.sendall(fake_mysql_greeting())
        finally:
            conn.close()


if __name__ == "__main__":
    main()
