# Operation ShadowTrace – Testing Checklist

## Test Summary

| Stage | Name | Functional | Flag Integrity | Isolation/Reset | Tester | Date | Notes |
|-------|------|------------|----------------|-----------------|--------|------|-------|
| 1 | OSINT – The Raven’s Trail | ☐ | ☐ | ☐ | | | |
| 2 | Stego – Hidden in Plain Sight | ☐ | ☐ | ☐ | | | |
| 3 | Network – Mapping the Nest | ☐ | ☐ | ☐ | | | |
| 4 | Web – The Broker’s Portal | ✅ | ✅ | ✅ |Hiruni Jayasundara | 11/10/2026| SQL Injection login bypass works. Flag + download ok|
| 5 | Forensics – Autopsy of a Payload | ☐ | ☐ | ☐ | | | |
| 6 | Linux – Root of the Collective | ☐ | ☐ | ☐ | | | |

## Test Criteria

### Functional Pass
- [ ] Stage starts correctly
- [ ] Intended solution path works
- [ ] Flag is obtainable

### Flag Integrity
- [ ] Correct flag is accepted
- [ ] Wrong flag is rejected
- [ ] Flag from another stage is rejected

### Isolation / Reset
- [ ] Cannot reach other stages directly
- [ ] No internet access from challenge
- [ ] Reset restores initial state
