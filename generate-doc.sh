#!/bin/sh

# Cleanup gh-pages
rm -rf gh-pages
mkdir gh-pages

# Get ApiGen.phar and Couscous
wget http://www.apigen.org/apigen.phar
wget http://couscous.io/couscous.phar

# Generate documentation
php couscous.phar generate --target gh-pages

# Generate Api
php apigen.phar generate

# Set identity
git config --global user.email "travis@travis-ci.org"
git config --global user.name "Travis"

# Add branch
cd ./gh-pages
git init
git remote add origin https://${GH_TOKEN}@github.com/llaumgui/CheckToolsFramework.git > /dev/null
git checkout -B gh-pages

# Push generated files
git add .
git commit -m "Doc updated"
git push origin gh-pages -fq > /dev/null
