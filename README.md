<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About This Project

This is a professional Laravel application with Filament admin panel featuring:

- **Enhanced Dashboard**: Professional stats overview with trends and mini-charts
- **Trending Posts**: Real-time tracking of most engaging content
- **Recent Activity Timeline**: Live feed of all platform activities
- **Top Contributors**: Leaderboard of most active users
- **Nord Theme**: Beautiful Arctic-inspired light/dark theme
- **Google Gemini AI Integration**: Content generation and moderation capabilities

## Dashboard Features

### 📊 Stats Overview
- Total Posts, Users, Comments with trend indicators
- Active Users (30-day rolling window)
- Average engagement metrics
- Weekly activity summary
- Mini sparkline charts for each metric

### 🔥 Trending Posts
Shows top 10 posts by comment count in the last 30 days with:
- Author information
- Comment counts
- Publication dates
- Quick view links

### 💬 Recent Comments
Latest 10 comments across all posts displaying:
- Comment content preview
- Associated post
- Author details
- Timestamp

### ⭐ Top Contributors
Leaderboard of most active users showing:
- Post counts (30 days)
- Comment counts (30 days)
- Total activity score
- Join date

### 📊 Activity Timeline
Live feed combining:
- New posts created
- Comments submitted
- Users joined
- Sorted chronologically with color-coded icons

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Admin Panel (Filament)

This project includes a Filament v3 admin panel registered via `App\Providers\Filament\AdminPanelProvider` and available at `/admin`.

### Features Implemented
- User management (`UserResource`) with author flag and password hashing.
- Post management (`PostResource`) with status tabs (Draft, Published, Archived) & soft delete support.
- Inline comment management for posts via `CommentsRelationManager` plus standalone `CommentResource`.

### Getting Started
1. Ensure dependencies are installed:
	```bash
	composer install
	npm install && npm run build
	```
2. Run migrations & seed sample data (optional):
	```bash
	php artisan migrate --seed
	```
3. Start the server:
	```bash
	php artisan serve
	```
4. (Optional during development) Run the Vite dev server in a separate terminal so Filament can stream assets without requiring a fresh build:
	```bash
	npm run dev -- --host
	```
5. Visit: `http://localhost:8000/admin` and log in with a seeded or created user.

### Customization Pointers
- Add new resources under `app/Filament/Resources`.
- Register additional panels by creating another PanelProvider in `app/Providers/Filament` and adding it to `bootstrap/providers.php`.
- To restrict access, implement policies or override `canAccessPanel()` in the panel provider.

### Next Ideas
- Add authorization (e.g. gates/policies for resources).
- Add global search configuration & widgets.
- Implement auditing for create/update/delete actions.
- Introduce filtering by author in Posts & Comments tables.
