#!/bin/sh
docker stop bootloader-generator
docker run -p 80:8080 -p 443:8443 --rm -d --name bootloader-generator kidoyen/bootloader-generator
