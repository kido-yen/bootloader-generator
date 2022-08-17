# bootloader-generator
This tool can be used to generate bootloader file for bootstrapping a system to PXE without having DHCP service running on the same network
Please note, secureboot needs to be disabled in BIOS since the bootloader is not signed with valid certificate.

# How to test the service
Simply run following command to start the service. The service will listen on port 80 and 443.
To start the service using pre-built container image, run start.sh.
To build the container image with preloaded ipxe image and start the service, run start-build.sh.
To build the container image with latest ipxe version and start the service, run start-clean_build.sh.
To build the latest ipxe image only, run build_ipxe.sh. The file can be located at /tmp/ipxe.efi on host.

# Parameters

<table>
  <tr><td>KidoBDF</td><td>0000:00:08.0</td><td>using designated NIC to boot</td></tr>
  <tr><td>KidoMAC</td><td>08:00:27:6b:53:eb</td><td>using designated NIC to boot</td></tr>
  <tr><td>KidoIP</td><td>10.5.12.120</td><td>IP Address</td></tr>
  <tr><td>KidoNETMASK</td><td>255.255.255.0</td><td>Netmask</td></tr>
  <tr><td>KidoGATEWAY</td><td>10.5.12.1</td><td>Gateway</td></tr>
  <tr><td>KidoDNS</td><td>8.8.8.8</td><td>DNS. only support single DNS server</td></tr>
  <tr><td>KidoFILENAME</td><td>http://10.5.15.10/test/bdf.ipxe</td><td>bootstrap file. need to be in iPXE format.</td></tr>
  <tr><td>format</td><td>iso</td><td>output format. default value is set to disk.</td></tr>
</table>
  
# Example
* http://10.102.14.83/bootloader/?KidoIP=10.102.14.111&KidoNETMASK=255.255.255.0&KidoGATEWAY=10.102.14.1&KidoFILENAME=http://10.7.21.51/bootstrap.php&KidoDNS=1.1.1.1&KidoMAC=52:54:00:75:6e:68&format=iso

* Use case - Test PXE in a network which does offer DHCP service but not include bootp information
http://10.102.14.83/bootloader/?KidoFILENAME=http://10.7.21.51/bootstrap.php

* Use case - Test PXE in a network which does not offer DHCP service 
http://10.102.14.83/bootloader/?KidoIP=10.102.14.111&KidoNETMASK=255.255.255.0&KidoGATEWAY=10.102.14.1&KidoFILENAME=http://10.7.21.51/bootstrap.php

* Use case - Test PXE  in a network which does not offer DHCP service, using designated NIC (MAC)
you may make use of redfish for IaC if multiple NICs are connected to the network(s)
http://10.102.14.83/bootloader/?KidoIP=10.102.14.111&KidoNETMASK=255.255.255.0&KidoGATEWAY=10.102.14.1&KidoFILENAME=http://10.7.21.51/bootstrap.php&KidoDNS=1.1.1.1&KidoMAC=52:54:00:75:6e:68

* Use case - Test PXE  in a network which does not offer DHCP service, using designated NIC (PCI BDF)
you may make use of redfish for IaC if multiple NICs are connected to the network(s)
http://10.102.14.83/bootloader/?KidoIP=10.102.14.111&KidoNETMASK=255.255.255.0&KidoGATEWAY=10.102.14.1&KidoFILENAME=http://10.7.21.51/bootstrap.php&KidoDNS=1.1.1.1&KidoBDF=0000:00:08.0

* Sample bootstrap file - boot a machine from network to RHEL8.x rescue mode.
can be used for BIOS/BMC firmware upgrade or create raid for a machine which does not have OS installed.
after booting to rescue mode, server can be accessed through ssh without supplying password using root account.
<pre>#!ipxe
imgfree
set base-url http://10.7.21.51/adt
initrd ${base-url}/centos7.8/isolinux/initrd.img
chain ${base-url}/centos7.8/isolinux/vmlinuz initrd=initrd.img ro rescue inst.sshd ksdevice=${mac} ip=${ip}::${gateway}:${netmask}:::none:: nameserver=8.8.8.8 nameserver=8.8.4.4 inst.stage2=${base-url}/centos7.8/
</pre>
