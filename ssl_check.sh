#!/bin/bash

result=$(echo|openssl s_client -servername $1 -connect $1:443 2>/dev/null|openssl x509 -noout -dates| sed 's/notAfter=/Expires On: /' | sed 's/notBefore=/Issued  On: /' | grep 'Expires On:' | sed 's/Expires On: //')
echo "$result"
