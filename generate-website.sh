#!/bin/sh

BUILD_PATH=gh-pages

# Cleanup gh-pages
rm -rf ${BUILD_PATH}
mkdir ${BUILD_PATH}

# Get ApiGen.phar and Couscous
wget http://www.apigen.org/apigen.phar
wget http://couscous.io/couscous.phar

# Generate documentation
php couscous.phar generate --target ${BUILD_PATH}

# Generate Api
php apigen.phar generate

# Build Phar
./bin/compile
mv phpct.phar ${BUILD_PATH}
mv phpct.version ${BUILD_PATH}

# Set identity
git config --global user.email "travis@travis-ci.org"
git config --global user.name "Travis"

# Add branch
cd ./${BUILD_PATH}
git init
git remote add origin https://${GH_TOKEN}@github.com/llaumgui/CheckToolsFramework.git > /dev/null
git checkout -B ${BUILD_PATH}

# Push generated files
git add .
git commit -m "Doc updated"
git push origin ${BUILD_PATH} -fq > /dev/null
