#!/bin/sh
image_name="ipxe_image"
docker build -f ./docker-images/ipxe-builder/Dockerfile --no-cache -t "${image_name}" .
docker create --name extract "${image_name}"
docker cp extract:/ipxe.efi /tmp
docker rm extract
docker rmi "${image_name}"
