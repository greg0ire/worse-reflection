#!/usr/bin/env bash
set -e
set -x

if [ -z "$TRAVIS_BRANCH" ]; then
    echo "PR is not a pull request (TRAVIS_BRANCH empty), skipping benchmarks"
    exit 0;
fi

git reset --hard
git remote add upstream https://github.com/phpactor/worse-reflection
git fetch upstream master $TRAVIS_BRANCH

echo -e "\n\n"
echo -e "Benchmarking master branch"
echo -e "==========================\n\n"
git checkout upstream/master
mv composer.lock composer.lock.pr
composer install --quiet
vendor/bin/phpbench run --report=aggregate_compact --progress=dots --retry-threshold=2 --tag=master

echo -e "\n\n"
echo -e "Benchmarking TRAVIS_BRANCH and comparing to master"
echo -e "==================================================\n\n"
git checkout $TRAVIS_BRANCH
git status
mv composer.lock.pr composer.lock
composer install --quiet
vendor/bin/phpbench run --report=aggregate_compact --progress=dots --retry-threshold=2 --uuid=tag:master 
