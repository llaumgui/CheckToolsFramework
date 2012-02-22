#!/bin/sh

# Install APiGen
composer require apigen/apigen apigen/theme-bootstrap:1.1.2

# Generate Api
vendor/bin/apigen generate

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
