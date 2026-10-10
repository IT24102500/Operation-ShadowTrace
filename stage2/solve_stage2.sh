#!/bin/bash
# Stage 2 solver - extracts hidden payload from stego image
IMAGE=$1
exiftool "$IMAGE"
steghide extract -sf "$IMAGE" -p "" -f
cat payload.txt


