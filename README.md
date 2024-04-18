## KShows

The list of Korean Movies and Korean Dramas. In this project, one can view the list and the individual details of the following:

-   Movies
-   TV Series
-   People

The details are fetched from the [The Movie Database](https://themoviedb.org).
Therefore, you will have to get the API Key and the Read Access Token after registering on their website.
Copy and paste the api key as value for the `TMDB_API_KEY` in the `.env` file.
Copy and paste the token as value for the `TMDB_READ_ACCESS_TOKEN` in the `.env` file.

### Installation

Run the following command one-by-one

```bash
git clone git@github.com:MehulBawadia/kshows.git
cd kshows
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan serve --host=localhost
npm run dev
```

#### License

This project is an open-sourced software licensed under the [MIT License](https://opensource.org/license/mit)
