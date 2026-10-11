#!/bin/bash
# Stage 3 known-good solver / functional test (Section 8 functional pass).
# Usage: ./solve_stage3.sh [host]

HOST="${1:-127.0.0.1}"

echo "[*] Full port scan (why 'don't just scan the top 1000 ports' matters):"
nmap -p- -sV "$HOST" 2>/dev/null || echo "    (nmap not installed - skipping)"

echo
echo "[*] Reading the verbose banner service on 31337:"
nc -w 3 "$HOST" 31337
