# Design Changes from Assignment 01

This document records any changes made to the approved Assignment 01 design during implementation.

| # | Change Description | Reason | Technical Justification | Member Responsible |
|---|--------------------|--------|--------------------------|--------------------|
| 1 | Replaced VM-based isolation (Section 6.1/6.3) with explicit per-stage Docker bridge networks |Faster build/reset for a 4-person student timeline; no VM licensing/host overhead |Docker bridge networks provide the same default-deny cross-segment behavior as the designed /28 VLANs — containers on different networks cannot route to each other without an explicit shared network, verified live via `docker exec ping` between stages |Herath H.M.T.S. (Member 1) |
| 2 | | | | |
| 3 | | | | |

## Notes

- If no changes are made, write “No changes from Assignment 01 design” in the table.
- Every change must be explained in the Member 4 video segment.
