# Stage 3 - SC-NET-01 : "Mapping the Nest"

Networking / Enumeration. Implements the spec in Section 5.3 of the
Assignment 01 report.

## What's in this box

| Item | Value |
|---|---|
| Exposed ports | `2201/tcp` → SSH (non-default, mapped from container `22`); `31337/tcp` → banner service |
| Internal-only service | fake-MySQL banner on container port `3306`, **not published to the host** |
| Flag | delivered by the banner service → `SHADOW{net_71f937}` |
| Hands off to Stage 4 | banner also prints the Stage 4 host address (placeholder `10.10.13.2:8080` until Stage 4 is actually deployed - update once it is) |

SSH on 2201 is intentionally a dead end for this stage: an account exists
(`svc-staging`) but with no credentials given to the player anywhere, and no
shell is needed to solve the stage. It's there so a `-sV` scan on the
non-default port looks and behaves like a real SSH service, matching the
report's "service-banner interpretation" learning objective — the player
has to notice SSH isn't the way in and that 31337 is.

## Intended solve path

1. `nmap -p- -sV <host>` — full range, not just the default top 1000 (first
   free hint), reveals SSH on 2201 and an unknown service on 31337.
2. `nc <host> 31337` (or `nmap --script banner -p 31337 <host>`) — the
   service immediately dumps its verbose status banner on connect.
3. Banner contains the flag fragment and the Stage 4 host:port.

The internal-only service on 3306 never shows up in an external scan at
all — it isn't published — so there's nothing to find there from outside
the box; it exists only to match the architecture description.

## Running it

```bash
cd stage3-network
docker compose up -d --build
```

Test it:

```bash
nmap -p- -sV localhost
nc localhost 31337
```

Or run the bundled solver:

```bash
chmod +x solve_stage3.sh
./solve_stage3.sh localhost
```

## Reset

No persistent state (stateful per the design, Section 7.3 lists Stage 3 as
restored within 30–90s):

```bash
docker compose down
docker compose up -d --build --force-recreate
```

## Isolation / security notes (Section 6.4 checklist)

- Container is on its own dedicated Docker network. True no-egress
  isolation (Section 6.4) needs a host-level firewall rule rather than
  Docker's `internal: true` flag, since that flag also blocks the NAT rules
  the `ports:` mapping needs — see the comment in `compose.yaml`.
- Only 2201 and 31337 are published, matching the Section 6.3 port table
  exactly; the internal-only service is never exposed to the host.
- `PermitRootLogin no`, no TCP/X11 forwarding on sshd.
- `svc-staging`'s password is a random value generated at build time and
  never printed anywhere — the account exists for realism only, not as a
  usable foothold.

## Wiring into the main stack

Kept standalone (`stage3-network/compose.yaml`) the same way `stage1-osint/`
and `stage6-linux/` are, rather than folded into the root `docker-compose.yml`.
Deploy it alongside the others with its own `docker compose up -d`, and once
Stage 4's actual container exists, update `STAGE4_ADDRESS` in
`banner_service.py` to its real host:port.
