#!/bin/bash

# --rm -dは併用出来ないらしい
docker run -d -p 8080:80 -v ~/GitRepository/Applications/Wolf/html:/var/www/html php:7.2.7-apache