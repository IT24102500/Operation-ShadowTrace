# Operation ShadowTrace – Testing Checklist

## Test Summary

| Stage | Name | Functional | Flag Integrity | Isolation/Reset | Tester | Date | Notes |
|-------|------|------------|----------------|-----------------|--------|------|-------|
| 1 | OSINT – The Raven’s Trail | ✅ | ✅ | ✅ | Lakmi Ylegama | 11/10/2026| |
| 2 | Stego – Hidden in Plain Sight | ✅ | ✅ | ✅ | Sithumini Herath | 11/10/2026| |
| 3 | Network – Mapping the Nest | ✅ | ✅ | ✅ | Arith Bandara | 11/10/2026| |
| 4 | Web – The Broker’s Portal | ✅ | ✅ | ✅ |Hiruni Jayasundara | 11/10/2026| SQL Injection login bypass works. Flag + download ok|
| 5 | Forensics – Autopsy of a Payload | ✅ | ✅ | ✅ | Lakmi Yalegama |11/10/2026 | |
| 6 | Linux – Root of the Collective | ✅ | ✅ | ✅ | Aritha Bandara | 11/10/2026| |

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
