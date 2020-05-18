#!/bin/bash
PHP=./pphp723/php/bin/php
PPHP=./linux_php
PPHP_LIB_DIR=./linux_php/libs

rm $PPHP -fr;
mkdir $PPHP_LIB_DIR -p;


PHP_BIN_LIB="$(find "$PHP" -type f -exec file -i '{}' \; | egrep 'x-executable; charset=binary|x-sharedlib; charset=binary' | awk -F ': ' '{print $1}')"
PHP_BIN="$(find "$PHP" -type f -exec file -i '{}' \; | grep 'x-executable; charset=binary' | awk -F ': ' '{print $1}')"
echo $PHP_BIN > bin.txt;
echo $PHP_BIN_LIB > bin_lib.txt;

cp /lib64/ld-linux-x86-64.so.2 $PPHP_LIB_DIR

for i in `cat bin_lib.txt`; do
    deps="$(ldd "$i" | awk -F ' ' '{print $3}' |grep '.so')"
    for j in "$deps"; do
        cp -n $j $PPHP_LIB_DIR
    done
done

# 设置运行时加载的扩展库
for i in `cat bin.txt`; do
    patchelf --set-rpath $PPHP_LIB_DIR --force-rpath $i
    patchelf --set-interpreter $PPHP_LIB_DIR/ld-linux-x86-64.so.2 $i
done
cp $PHP $PPHP;
rm bin.txt bin_lib.txt -fr