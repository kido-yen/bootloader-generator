FROM registry.access.redhat.com/ubi8/php-74:1-38

# Copy base image of bootloader, php program, and apache configuration files to DocumentRoot/bootloader
COPY --chown=1001:0 libusal-1.1.11-39.el8.x86_64.rpm genisoimage-1.1.11-39.el8.x86_64.rpm bootloader_base.img index.php httpd.conf mtools-4.0.18-14.el8.x86_64.rpm /opt/app-root/src/bootloader/

# run following commands as root 
USER root

# Move http configuration to proper location
RUN mv -f /opt/app-root/src/bootloader/httpd.conf /etc/httpd/conf/httpd.conf \
    && rpm -ivh /opt/app-root/src/bootloader/mtools-4.0.18-14.el8.x86_64.rpm \
    && rpm -ivh /opt/app-root/src/bootloader/libusal-1.1.11-39.el8.x86_64.rpm \
    && rpm -ivh /opt/app-root/src/bootloader/genisoimage-1.1.11-39.el8.x86_64.rpm \
    && rm -f /opt/app-root/src/bootloader/mtools-4.0.18-14.el8.x86_64.rpm \
    && rm -f /opt/app-root/src/bootloader/libusal-1.1.11-39.el8.x86_64.rpm \
    && rm -f /opt/app-root/src/bootloader/genisoimage-1.1.11-39.el8.x86_64.rpm
# start service as 1001
USER 1001
# specify entrypoint for service
ENTRYPOINT ["httpd", "-D", "FOREGROUND"]
