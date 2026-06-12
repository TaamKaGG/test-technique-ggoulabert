#!/bin/bash

echo "start symfony"
symfony server:stop
symfony run -d --watch=config,src,templates,vendor symfony console messenger:consume async -vv
symfony server:start --no-tls -d
symfony open:local  
symfony server:log