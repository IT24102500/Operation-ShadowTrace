# Stage 5 — Digital Forensics: Autopsy of a Payload

## Challenge overview
Investigate a simulated forensic evidence archive to recover an encoded configuration.

## Player files
- `incident.zip` — downloadable challenge evidence archive.

## Investigation techniques
- Examine authentication and access logs.
- Review the incident timeline and recovered note.
- Identify the Base64 encoding and single-byte XOR transformation.
- Decode the artifact safely using Python.

## Safety
The artifact is simulated data for an educational CTF.
Do not execute suspicious artifacts.

## Deployment
Upload `incident.zip` as the attachment for the Stage 5 challenge in CTFd.
Configure the expected flag in CTFd separately from the player-facing files.
