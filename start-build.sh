#!/bin/sh
OFS=$IFS
IFS=$'\n'
container_name="bootloader-generator"
image_name="bootloader-generator:latest"

# stop running container
for container in $(docker ps --filter "name=${container_name}"|sed '1d');do docker stop "${container_name}"; done

# remove containers with exited status
for container_id in $(docker ps --filter "status=exited" --format "{{.ID}}");do docker rm "${container_id}"; done

# remove existing container image
for image in $(docker images "${image_name}");do docker rmi "${image_name}";done

# build container image
docker build -f ./docker-images/bootloader-generator/Dockerfile --no-cache -t "${image_name}" .
# start container
docker run -p 80:8080 -p 443:8443 --rm  -d --name "${container_name}" "${image_name}"
