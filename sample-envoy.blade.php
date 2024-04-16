@servers(['web' => 'username@ip_address -p <port-number>'])

@task('deploy')
    {{-- Replace username and domain-name with the respective values --}}
    cd /home/username/domains/domain-name
    echo "Inside domain-name directory..."

    rm -rf vendor/ node_modules/ package-lock.json
    echo "Removed vendor/ directory and node_modules/ directory"

    git reset --hard origin/main
    echo "Removed any untracked files and/or folders"

    git pull origin main

    composer2 install --optimize-autoloader --no-dev

    php artisan config:clear
    php artisan route:clear
    php artisan view:clear

    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    export NVM_DIR="$([ -z "${XDG_CONFIG_HOME-}" ] && printf %s "${HOME}/.nvm" || printf %s "${XDG_CONFIG_HOME}/nvm")"
    [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
    nvm use 20.9.0

    npm install

    npm build

    rm -rf node_modules/
    echo "Removed node_modules/ directory.."

    echo "Check https://subdomain.domain-name.com"

    {{--
        For more information, visit the following link:
        https://bmehul.com/articles/deploy-laravel-app-on-shared-server-using-envoy
    --}}
@endtask
