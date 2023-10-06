@servers(['web' => 'root@134.122.4.168'])

@task('deploy')
    cd /home/unify_laravel
    git pull origin main -X theirs
    sh build.sh
    docker-compose down -v && docker-compose up -d
    docker-compose exec app php artisan migrate
    docker image rm $(docker image ls -f 'dangling=true' -q)
@endtask
