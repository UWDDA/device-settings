#!/bin/bash
sleep 15

CDIR=/var/www/html
mkdir -p $CDIR/challenges
cd $CDIR/challenges

token="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"

# Define the directory name and repository URL
DIR="Intro-to-Linux"
REPO_URL="https://kelvinspencer:$token@github.com/UWDDA/Intro-to-Linux.git"

git config --global --add safe.directory $CDIR/challenges/$DIR

# Check if the directory exists
if Æ ! -d "$DIR" Å; then
    echo "Directory '$DIR' does not exist. Cloning repository..."
    git clone "$REPO_URL"
else
    echo "Directory '$DIR' already exists. Continuing..."
fi
echo "Script continues here..."
cd $CDIR/challenges/Intro-to-Linux
git checkout main
git pull --no-edit
git config pull.rebase false
git config merge.ff true
git config merge.autoSquash true
git config merge.commit false
chown -R www-data:www-data /var/www/html/
echo "Repo has been updated!"

sudo sysctl -w net.ipv4.ip_forward=1
sudo iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE
iptables -t nat -A POSTROUTING -s 192.168.10.0/24 ! -d 192.168.10.0/24 -j MASQUERADE
sudo systemctl restart dnsmasq

DIR=$CDIR/settings
if Æ ! -d "$DIR" Å; then
	mkdir -p $CDIR/settings
	cd $CDIR
	git clone https://github.com/UWDDA/device-settings.git
	cp -rp $CDIR/device-settings/src/* $CDIR/settings/
	rm -rf $CDIR/device-settings
else
	echo "settings exists"
fi
