<?php

namespace Deployer;

require 'recipe/common.php';

// Configuration
set('application', 'Hn Validator');
set('repository', 'https://github.com/akramkies/seoHnTagValidatorInterface.git');
set('default_stage', 'production');
set('ssh_multiplexing', true);

// Hosts
host('seovalidator.alwaysdata.net')
    ->stage('production')
    ->user('seovalidator')
    ->set('deploy_path', '/path/to/deploy/{{application}}');

// Tasks
task('deploy:shared', function () {
    // Créer les liens symboliques vers les dossiers partagés
    run('mkdir -p {{deploy_path}}/shared/storage');
    run('mkdir -p {{deploy_path}}/shared/public/uploads');
    run('ln -s {{deploy_path}}/shared/storage {{release_path}}/storage');
    run('ln -s {{deploy_path}}/shared/public/uploads {{release_path}}/public/uploads');
});

// Laravel tasks
task('artisan:storage:link', function () {
    run('{{bin/php}} {{release_path}}/artisan storage:link');
});

task('artisan:migrate', function () {
    run('{{bin/php}} {{release_path}}/artisan migrate --force');
});

task('artisan:cache:clear', function () {
    run('{{bin/php}} {{release_path}}/artisan cache:clear');
});

// Déployer les tâches Laravel spécifiques
before('artisan:migrate', 'artisan:storage:link');
after('deploy:symlink', 'artisan:migrate');
after('deploy:failed', 'deploy:unlock');
after('deploy:failed', 'deploy:unlock');

// Execution des tâches
after('deploy:shared', 'deploy:writable');
after('deploy:failed', 'deploy:unlock');