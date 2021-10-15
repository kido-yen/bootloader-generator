#!/bin/sh
config=/ipxe/src/config/general.h
option="NSLOOKUP_CMD REBOOT_CMD POWEROFF_CMD PCI_CMD PING_CMD CONSOLE_CMD NET_PROTO_IPV6 PARAM_CMD"
sed_opt=""
for opt in $option;do
  opt="s/\/\/#define ${opt}/#define ${opt}/g;"
  sed_opt="${sed_opt}${opt}"
done
sed -i "${sed_opt}" ${config}
