FROM registry.access.redhat.com/ubi8/ubi:8.4-211 AS builder

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



FROM registry.access.redhat.com/ubi8/php-74:1-38

# Copy base image of bootloader, php program, and apache configuration files to DocumentRoot/bootloader
COPY --chown=1001:0 libusal-1.1.11-39.el8.x86_64.rpm genisoimage-1.1.11-39.el8.x86_64.rpm bootloader_base.img index.php httpd.conf mtools-4.0.18-14.el8.x86_64.rpm /opt/app-root/src/bootloader/

# run following commands as root
USER root

COPY --from=builder /ipxe.efi /opt/app-root/src/bootloader/

WORKDIR /opt/app-root/src/bootloader/
# Move http configuration to proper location
RUN mv -f httpd.conf /etc/httpd/conf/httpd.conf \
    && rpm -ivh mtools-4.0.18-14.el8.x86_64.rpm \
    && rpm -ivh libusal-1.1.11-39.el8.x86_64.rpm \
    && rpm -ivh genisoimage-1.1.11-39.el8.x86_64.rpm \
    && rm -f mtools-4.0.18-14.el8.x86_64.rpm \
    && mcopy -o -i bootloader_base.img ipxe.efi "::/efi/boot/bootx64.efi" \
    && rm -f ipxe.efi 

# start service as 1001
USER 1001
# specify entrypoint for service
ENTRYPOINT ["httpd", "-D", "FOREGROUND"]
