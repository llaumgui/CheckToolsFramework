#!/bin/sh

# Get ApiGen.phar
wget http://www.apigen.org/apigen.phar

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
git commit -m "API updated"
git push origin gh-pages -fq > /dev/null
