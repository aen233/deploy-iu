<?php

namespace Deployer;

require 'recipe/laravel.php';

set('repository', 'https://github.com/aen233/iu.git');
add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);
// 顺便把 composer 的 vendor 目录也加进来
add('copy_dirs', ['node_modules', 'vendor']);

host('111.22.3.4')
    ->user('root')// 使用 root 账号登录
    ->identityFile('~/.ssh/aen233.pem')// 指定登录密钥文件路径
    ->set('deploy_path', '/var/www/html/iu-deployer'); // 指定部署目录

// 定义一个上传 .artisan_env 文件的任务
desc('Upload .artisan_env file');
task('artisan_env:upload', function () {
    // 将本地的 .env 文件上传到代码目录的 .env
    upload('.artisan_env', '{{release_path}}/.env');
});

// 定义一个上传 .env 文件的任务
desc('Upload .env file');
task('env:upload', function () {
    // 将本地的 .env 文件上传到代码目录的 .env
    upload('.env', '{{release_path}}/.env');
});

task('chmod', function () {
    run('cd {{release_path}} && chmod 777 -R storage/');
});

// 在 deploy:vendors 之前调用 deploy:copy_dirs
before('deploy:vendors', 'deploy:copy_dirs');

before('artisan:storage:link', 'artisan:migrate');
before('artisan:migrate', 'artisan_env:upload');
after('artisan:migrate', 'env:upload');

after('deploy:failed', 'deploy:unlock');
after('cleanup', 'chmod');
