FROM registry.access.redhat.com/ubi8/ubi:8.4-211

# Copy base image of bootloader, php program, and apache configuration files to DocumentRoot/bootloader
COPY patch_ipxe.sh kido.ipxe /

# change workdir to /
WORKDIR /

# run following commands as root 
USER root

# Install required packages
# Download ipxe source code
# Patch ipxe configuration to enable designated features
# clean up
RUN dnf install -y git make gcc \
    && git clone git://git.ipxe.org/ipxe.git \
    && cd ipxe/src \
    && mv /kido.ipxe . \
    && sh /patch_ipxe.sh \
    && make bin-x86_64-efi/ipxe.efi EMBED=kido.ipxe \
    && mv bin-x86_64-efi/ipxe.efi / \
    && rm -rf /ipxe /patch_ipxe.sh \ 
    && yum clean all
