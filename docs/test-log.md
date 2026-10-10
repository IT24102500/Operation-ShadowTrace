# Test Log – Operation ShadowTrace

## Stage 4 – The Broker’s Portal
**Tester:** Jayasundara J.M.H.P. (Member 4)  
**Date:** 11/10/2026

### Tests Performed
1. Functional – Login page loads on http://localhost:8080
2. SQL Injection – Payload `' OR 1=1--` bypasses authentication
3. Flag – SHADOW{web_8c3d7a} visible on admin panel
4. Download – payload.zip downloads successfully
5. Automation – test_sqli.php returns SUCCESS
6. Reset – docker compose down/up restores working state

### Result
All Stage 4 tests PASSED.

### Defects Found
None.
