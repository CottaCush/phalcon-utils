#!/usr/bin/env bash

git checkout develop

git pull origin develop

latest_git_commit_id=`git rev-list --tags --max-count=1`
current_version=`git describe --tags ${latest_git_commit_id}`
echo "Please enter release version (current version is $current_version):"

read release_version

script_dir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
php ${script_dir}/bump_composer_version.php ${release_version}

git add composer.json

git commit -m "bump composer.json version to $release_version"

git push origin develop

git checkout master

git merge develop

git push origin master

echo "Please enter tag message for $release_version"

read tag_message

git tag -a ${release_version} -m "${tag_message}"

git push --tags

git checkout develop

latest_git_commit_id=`git rev-list --tags --max-count=1`

today=`date +'%Y-%m-%d'`
echo -e "\n# [$release_version](https://bitbucket.org/cottacush/phalcon-utils/src/$latest_git_commit_id/?at=$release_version) ($today)" >> ${script_dir}/../CHANGELOG.md

echo "Please add release change logs"

if hash sublime 2>/dev/null; then
    sublime -w ${script_dir}/../CHANGELOG.md
else
    vim ${script_dir}/../CHANGELOG.md
fi

git add CHANGELOG.md

git commit -m "Add changelog for $release_version"

git push origin develop

git checkout master

git merge develop

git push origin master

git checkout develop

slack_update=`git diff  --no-ext-diff --unified=0 --exit-code -a --no-prefix ${latest_git_commit_id}..HEAD ${script_dir}/../CHANGELOG.md | egrep "^\+"`
echo ${slack_update} >> ${script_dir}/slack_update.temp
slack_update=`sed  's .  ' ${script_dir}/slack_update.temp`
echo ${slack_update} > ${script_dir}/slack_update.temp
slack_update=`tail -n +3 ${script_dir}/slack_update.temp`
rm ${script_dir}/slack_update.temp

PAYLOAD="payload={\"channel\": \"#phalcon-utils\", \"username\": \"Phalcon Utils Release Bot\", \"text\": \"Phalcon Utils {$release_version} has been released \n\n${slack_update}\", \"icon_emoji\": \":rat:\"}";
curl -s -S -X POST --data-urlencode "$PAYLOAD" https://hooks.slack.com/services/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

echo "Release done"

