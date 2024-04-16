## KShows

The list of Korean Movies and Korean Dramas. In this project, one can view the list and the individual details of the following:

-   Movies
-   TV Series
-   People

The details are fetched from the [The Movie Database](https://themoviedb.org).
Therefore, you will have to get the Read Access Token after registering on their website.
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

#### Notes

Sometimes, while viewing it live, it will display 500 error.
This error comes from the hosting provider that the app is hosted on.
Wait for few minutes, it will display the page properly.

#### Live Demo

You can check the [live demo here](https://kshows.bmehul.com)

#### License

This project is an open-sourced software licensed under the [MIT License](https://opensource.org/license/mit)
