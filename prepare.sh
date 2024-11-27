#!/bin/bash
mkdir phpt_seeds;
mkdir phpt_deps;
git clone https://github.com/php/php-src.git;
cd php-src; find ./ -name "*.phpt" > /tmp/flowfusion-prepare.log;
cd ..; python3 prepare.py;