# Retrospective Guild Website

A modern, responsive website for the Retrospective Guild

## Features

- 🎮 Guild information and recruitment system
- 📰 Dynamic news system with admin panel
- 🎵 Background music and Polish anthem in admin panel
- 🌟 Animated starry background
- 📱 Fully responsive design
- 🔒 Secure admin authentication
- 📊 User tracking system

## Project Structure

```
Retrospective Website/
├── frontend/
│   ├── css/          # Stylesheets
│   ├── js/           # JavaScript files
│   ├── images/       # Frontend-specific images
│   ├── index.php     # Main page
│   └── aboutme.html  # About page
├── backend/
│   ├── config/       # Configuration files
│   │   ├── database.php
│   │   ├── database.sql
│   │   └── check_db.php
│   ├── includes/     # PHP includes
│   │   └── navbar.php
│   ├── adminpanel.php
│   ├── admin.php
│   ├── news.php
│   └── script.php
└── assets/
    ├── audio/        # Audio files
    │   ├── hymnpolski.mp3
    │   └── background.mp3
    └── images/       # Shared images
        ├── logo.png
        └── logo.jpg
```

## Requirements

- PHP 7.4 or higher
- MySQL/MariaDB
- Web server (Apache/Nginx)

## Installation

1. Clone the repository
2. Import the database schema from `backend/config/database.sql`
3. Configure database connection in `backend/config/database.php`
4. Set up your web server to point to the project directory
5. Ensure proper permissions are set for file uploads and logs

## Configuration

Database configuration can be modified in `backend/config/database.php`:

```php
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'your_database');
```

## Features in Detail

### Admin Panel
- Secure login system
- News management (add/delete)
- User tracking
- Background music control
- Polish anthem playback

### Frontend
- Responsive design
- Dynamic news display
- Guild information section
- Recruitment form
- Discord integration
- Animated backgrounds

### Security
- SQL injection prevention
- XSS protection
- Secure session management
- Input validation

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is proprietary and confidential. All rights reserved.

## Author

Created by tari (v1.1.0) 
