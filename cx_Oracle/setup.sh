#!/bin/bash

echo "Checking if stash public key is in known hosts"
NIMHDA_KEY=`ssh-keyscan -p 7999 git.nimhda.org`
if grep --quiet "$NIMHDA_KEY" /home/qapipeline/.ssh/known_hosts;
then
    echo "Stash public key already in known_hosts."
else
    echo "$NIMHDA_KEY" >> /home/qapipeline/.ssh/known_hosts
fi

echo "Setting up cx_Oracle."
echo "..Checking dependencies. "
echo "....Checking if Oracle client and SDK are present."

if  [ ! -e '/usr/lib/instantclient-basic-linux.x64-11.2.0.4.0.zip' ] \
 && [ ! -d '/usr/lib/instantclient_11_2' ]
then
    if [ -e /home/qapipeline/qapipeline/Utils/cx_Oracle/instantclient-basic-linux.x64-11.2.0.4.0.zip ]
    then
        cp /home/qapipeline/qapipeline/Utils/cx_Oracle/instantclient-basic-linux.x64-11.2.0.4.0.zip /usr/lib
    else
        echo  "......Missing instantclient-basic, retreiving from stash via ssh."
        git archive --remote=ssh://git@git.nimhda.org:7999/aq/qapipeline.git \
            HEAD:Utils/cx_Oracle instantclient-basic-linux.x64-11.2.0.4.0.zip | tar -x
        cp instantclient-basic-linux.x64-11.2.0.4.0.zip /usr/lib 
    fi
    unzip /usr/lib/instantclient-basic-linux.x64-11.2.0.4.0.zip -d /usr/lib
else
    echo "......instantclient-basic-linux.x64-11.2.0.4.0.zip is present."
fi

if [ ! -e '/usr/lib/instantclient-sdk-linux.x64-11.2.0.4.0.zip' ]
then

    if [ -e /home/qapipeline/qapipeline/Utils/cx_Oracle/instantclient-sdk-linux.x64-11.2.0.4.0.zip ]
    then

        cp /home/qapipeline/qapipeline/Utils/cx_Oracle/instantclient-sdk-linux.x64-11.2.0.4.0.zip /usr/lib
    else

        echo "......Missing instantclient-sdk, retreiving froms tash via ssh."
        git archive --remote=ssh://git@git.nimhda.org:7999/aq/qapipeline.git \
            HEAD:Utils/cx_Oracle instantclient-sdk-linux.x64-11.2.0.4.0.zip | tar -x
        cp instantclient-sdk-linux.x64-11.2.0.4.0.zip /usr/lib
    fi

    unzip /usr/lib/instantclient-sdk-linux.x64-11.2.0.4.0.zip -d /usr/lib
else
    echo "......instantclient-sdk-linux.x64-11.2.0.4.0.zip is present."
fi

echo "....Checking if oracle library is linked."
if [ ! -e /usr/lib/instantclient_11_2/libclntsh.so ]
then
    echo ".....Library not linked, linking now.."
    ln -s /usr/lib/instantclient_11_2/libclntsh.so.11.1 /usr/lib/instantclient_11_2/libclntsh.so
else
    echo ".....libclntsh.so.11.1 is linked to libclntsh.so."
fi

echo "..Checking if paths for ORACLE_HOME and LD_LIBRARY_PATH are set for all users..."
if [ -e '/etc/profile.d/cx_oracle.sh' ]
then
    echo "....The file /etc/profile.d/cx_oracle.sh already exists. Check that setup has not been run previously."
else
    if [ "$ORACLE_HOME" = "/usr/lib/instantclient_11_2" ]
    then
        echo "....ORACLE_HOME is already set correctly to /usr/lib/instantclient_11_2."
    else

      echo "....Environment variables are not set, creating /etc/profile.d/cx_oracle.sh to set for all users."

ORACLE_HOME=/usr/lib/instantclient_11_2
cat > /etc/profile.d/cx_oracle.sh <<EOL
export ORACLE_HOME=$ORACLE_HOME
export LD_LIBRARY_PATH=$LD_LIBRARY_PATH:$ORACLE_HOME
export PATH=$PATH:"$ORACLE_HOME/bin"
EOL

    fi
fi

echo "..Checking if cx_Oracle-5.1.2 library is available."

if [ ! -e '/usr/lib/cx_Oracle-5.1.2.tar.gz' ] \
&& [ ! -e '/usr/lib/cx_Oracle-5.1.2' ]
then
    if [ -e /home/qapipeline/qapipeline/Utils/cx_Oracle/cx_Oracle-5.1.2.tar.gz  ] \
    && [ ! -e /usr/lib/cx_Oracle-5.1.2.tar.gz ]
    then
        cp /home/qapipeline/qapipeline/Utils/cx_Oracle/cx_Oracle-5.1.2.tar.gz /usr/lib
    else
        echo "....Not available, downloading the cx_Oracle python package, version 5.1.2."
        git archive --remote=ssh://git@git.nimhda.org:7999/aq/qapipeline.git \
            HEAD:Utils/cx_Oracle cx_Oracle-5.1.2.tar.gz | tar -x
        cp cx_Oracle-5.1.2.tar.gz /usr/lib
    fi
else
    echo "....Library is available."
fi

if [ ! -d "/usr/lib/cx_Oracle-5.1.2/" ]
then
    echo "....Extracting cx_Oracle library."
    cd /usr/lib
    tar -xvf /usr/lib/cx_Oracle-5.1.2.tar.gz
fi

if [ ! -e '/usr/local/lib/python2.7/dist-packages/cx_Oracle-5.1.2-py2.7-linux-x86_64.egg' ]
then
    echo "Installing cx_Oracle package..."
    source /etc/profile.d/cx_oracle.sh
    cd /usr/lib/cx_Oracle-5.1.2
    echo "Installing cx_Oracle."
    python setup.py install
else
    echo "Package already installed, open interactive console and import cx_Oracle to verify."
fi