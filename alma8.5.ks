#version=RHEL8
# Use graphical install
graphical

url --url=https://hkg.mirror.rackspace.com/almalinux/8.5/BaseOS/x86_64/kickstart/
repo --name="AppStream" --baseurl=https://hkg.mirror.rackspace.com/almalinux/8.5/AppStream/x86_64/kickstart/

%packages
@^minimal-environment

%end

# Keyboard layouts
keyboard --xlayouts='us'
# System language
lang en_US.UTF-8

# Network information
network  --bootproto=dhcp --device=enp0s3 --ipv6=auto --activate
network  --hostname=localhost.localdomain

# Use CDROM installation media
cdrom

# Run the Setup Agent on first boot
firstboot --enable

ignoredisk --only-use=nvme0n1
# Partition clearing information
#clearpart --none --initlabel
clearpart --all --initlabel --drives=nvme0n1
# Disk partitioning information
part /boot/efi --fstype="efi" --ondisk=nvme0n1 --size=100 --fsoptions="umask=0077,shortname=winnt"
part / --fstype="xfs" --ondisk=nvme0n1 --size=5746
part /boot --fstype="xfs" --ondisk=nvme0n1 --size=1024

# System timezone
timezone America/New_York --isUtc

# Root password
rootpw --iscrypted $6$Oqe1zzuqzBSOHIIa$V/4KbgSPHSE/rAKHOELUeyqLRycBEHgJaFXX.5hDKjMzecFrGwTAbFkgvheXrroH8Ta.DmW33Kp3bHa5RZ1Ab0

%addon com_redhat_kdump --disable --reserve-mb='auto'

%end

%anaconda
pwpolicy root --minlen=6 --minquality=1 --notstrict --nochanges --notempty
pwpolicy user --minlen=6 --minquality=1 --notstrict --nochanges --emptyok
pwpolicy luks --minlen=6 --minquality=1 --notstrict --nochanges --notempty
%end
