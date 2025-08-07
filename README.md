# Laravel Admin Panel

A comprehensive admin panel built with Laravel for managing invoices, payments, clients, vendors, employees, and financial transactions.

## 🚀 Features

### Core Features
- **User Authentication & Authorization**
  - Login with email/password
  - OTP (One-Time Password) verification
  - Session management
  - Role-based access control

- **Invoice Management**
  - Create, edit, and delete invoices
  - GST calculation and management
  - Invoice status tracking (Draft, Sent, Paid, Overdue)
  - PDF generation
  - Payment tracking

- **Payment Management**
  - Multiple payment methods
  - Payment tracking and history
  - Payment status management
  - Internal transfers

- **Client Management**
  - Client registration and profiles
  - GST registration status
  - Client payment history
  - Client categorization

- **Vendor Management**
  - Vendor registration and profiles
  - Vendor categories
  - Vendor payment tracking
  - Due amount management

- **Employee Management**
  - Employee registration and profiles
  - Employee categories
  - Salary and payment tracking
  - Employee due management

- **Financial Management**
  - Income and expense tracking
  - Category-based financial management
  - Account management
  - Financial reports

- **Subscription Management**
  - Client subscription tracking
  - Subscription payment management
  - Payment history

## 🛠️ Technology Stack

- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates, Bootstrap 5
- **Database**: MySQL
- **Authentication**: Laravel Fortify
- **Styling**: Custom CSS, Tailwind CSS (partial)
- **JavaScript**: Vanilla JS, Bootstrap JS
- **Email**: Laravel Mail with custom templates

## 📋 Requirements

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Web Server (Apache/Nginx)
- Node.js & NPM (for asset compilation)

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/laravel-admin-panel.git
cd laravel-admin-panel
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Configure Mail Settings
```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email
MAIL_FROM_NAME="${APP_NAME}"
```

### 6. Run Migrations
```bash
php artisan migrate
```

### 7. Seed Database (Optional)
```bash
php artisan db:seed
```

### 8. Compile Assets
```bash
npm run dev
# or for production
npm run build
```

### 9. Set Permissions
```bash
chmod -R 775 storage bootstrap/cache
```

### 10. Create Storage Link
```bash
php artisan storage:link
```

## 📁 Project Structure

```
admin_panel/
├── app/
│   ├── Actions/Fortify/          # Authentication actions
│   ├── Console/Commands/         # Custom Artisan commands
│   ├── Http/Controllers/         # Application controllers
│   │   ├── Admin/               # Admin panel controllers
│   │   └── Auth/                # Authentication controllers
│   ├── Mail/                    # Email templates
│   ├── Models/                  # Eloquent models
│   └── Providers/               # Service providers
├── config/                      # Configuration files
├── database/
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders
├── public/
│   ├── css/                     # Compiled CSS files
│   └── js/                      # JavaScript files
├── resources/
│   ├── css/                     # Source CSS files
│   ├── js/                      # Source JavaScript files
│   └── views/                   # Blade templates
│       ├── admin/               # Admin panel views
│       ├── auth/                # Authentication views
│       ├── components/          # Reusable components
│       └── emails/              # Email templates
└── routes/                      # Route definitions
```

## 🔐 Authentication System

### Login Flow
1. User enters email and password
2. System validates credentials
3. OTP code is generated and sent via email
4. User enters OTP code for verification
5. User is redirected to dashboard upon successful verification

### OTP System
- 6-digit numeric codes
- Email delivery via Laravel Mail
- Session-based verification
- Automatic expiration

## 📊 Database Schema

### Core Tables
- `users` - User accounts and authentication
- `clients` - Client information and profiles
- `invoices` - Invoice data and status
- `payments` - Payment transactions
- `vendors` - Vendor information
- `employees` - Employee data
- `categories` - Various categorization tables
- `accounts` - Financial accounts
- `income_expenses` - Financial transactions

### Key Relationships
- Users have many invoices, payments, clients
- Clients have many invoices and payments
- Vendors have many payments and dues
- Employees have many payments and dues
- Categories organize various entities

## 🎨 UI/UX Features

### Design System
- Dark theme with professional styling
- Responsive design for all devices
- Bootstrap 5 components
- Custom CSS for branding
- Font Awesome icons

### Components
- Modal dialogs for forms
- Data tables with pagination
- Form validation and error handling
- Loading states and feedback
- Toast notifications

## 📧 Email System

### Email Templates
- **Login OTP**: Professional design with company branding
- **Password Reset**: Secure password reset functionality
- **Invoice Notifications**: Invoice status updates

### Email Features
- Responsive email design
- Inline CSS for compatibility
- Professional branding
- Clear call-to-action buttons

## 🔧 Configuration

### Environment Variables
```env
# Application
APP_NAME="Admin Panel"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

## 🚀 Deployment

### For InfinityFree Hosting
1. Upload files to your hosting directory
2. Set database credentials in `.env`
3. Run migrations: `php artisan migrate`
4. Set proper permissions
5. Configure email settings

### For VPS/Dedicated Server
1. Set up LAMP/LEMP stack
2. Configure virtual host
3. Install Composer and dependencies
4. Set up SSL certificate
5. Configure cron jobs for scheduled tasks

## 🔒 Security Features

- CSRF protection
- SQL injection prevention
- XSS protection
- Input validation and sanitization
- Secure password hashing
- Session security
- Rate limiting on authentication

## 📱 Responsive Design

- Mobile-first approach
- Tablet optimization
- Desktop compatibility
- Touch-friendly interfaces
- Cross-browser compatibility

## 🧪 Testing

```bash
# Run feature tests
php artisan test

# Run specific test file
php artisan test --filter=AuthenticationTest
```

## 📈 Performance Optimization

- Database query optimization
- Asset minification
- Caching strategies
- Image optimization
- CDN integration (optional)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:
- Create an issue on GitHub
- Contact: support@yourdomain.com
- Documentation: [Wiki](https://github.com/yourusername/laravel-admin-panel/wiki)

## 🔄 Version History

### v1.0.0 (Current)
- Initial release
- Complete admin panel functionality
- Authentication system
- Financial management
- Responsive design

## 🙏 Acknowledgments

- Laravel team for the amazing framework
- Bootstrap team for the UI components
- Font Awesome for the icons
- All contributors and testers

---

**Made with ❤️ using Laravel**
