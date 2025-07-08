# AI Email Assistant - Setup Instructions

This Laravel 11 application provides an AI-powered email assistant that integrates with Gmail to generate and send professional email replies using OpenAI's ChatGPT.

## Features

✅ **Authentication** - Laravel Breeze with test user `admin@demo.com` / `password123`  
✅ **Gmail Integration** - OAuth2 flow, fetch emails, send replies  
✅ **AI Replies** - ChatGPT-3.5 powered email responses  
✅ **Modern UI** - TailwindCSS responsive design  
✅ **SQLite Database** - Easy local setup  

## Prerequisites

- PHP 8.1+
- Composer
- Node.js & NPM
- SQLite
- Google API Console access
- OpenAI API key

## Installation Steps

### 1. Install Dependencies
```bash
cd ai-email-assistant
composer install
npm install
```

### 2. Configure Environment
```bash
# Copy environment file
cp .env.example .env

# Generate application key (if not already done)
php artisan key:generate
```

### 3. Set up Google API Credentials

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable the Gmail API
4. Create OAuth 2.0 credentials:
   - Application type: Web application
   - Authorized redirect URIs: `http://127.0.0.1:8000/google/callback`
5. Download credentials and update `.env`:

```env
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/google/callback
```

### 4. Set up OpenAI API

1. Get API key from [OpenAI Platform](https://platform.openai.com/api-keys)
2. Update `.env`:

```env
OPENAI_API_KEY=your_openai_api_key_here
```

### 5. Database Setup
```bash
# Create SQLite database (already created during setup)
touch database/database.sqlite

# Run migrations and seed test user
php artisan migrate
php artisan db:seed
```

### 6. Build Assets
```bash
npm run build
```

### 7. Start Development Server
```bash
php artisan serve
```

The application will be available at `http://127.0.0.1:8000`

## Usage

### 1. Login
- Email: `admin@demo.com`
- Password: `password123`

### 2. Connect Gmail
1. Click "Connect Gmail" on the dashboard
2. Authorize with your Google account
3. Grant necessary permissions

### 3. Use AI Features
1. Go to "View Inbox" to see your emails
2. Click "Generate AI Reply" on any email
3. Review the AI-generated response
4. Click "Send AI Reply" to send it

## Available Routes

- `/` - Welcome page
- `/login` - Authentication
- `/dashboard` - Main dashboard
- `/google` - Initiate Gmail OAuth
- `/google/callback` - Handle OAuth callback
- `/inbox` - View Gmail inbox
- `/generate-reply/{messageId}` - Generate AI reply
- `/send-reply/{messageId}` - Send AI reply
- `/email/{messageId}` - Get email details
- `/email/{messageId}/summary` - Generate AI summary

## Project Structure

```
ai-email-assistant/
├── app/
│   ├── Http/Controllers/
│   │   ├── GmailController.php      # Gmail OAuth & email fetching
│   │   └── EmailController.php      # AI reply generation & sending
│   └── Services/
│       ├── GmailService.php         # Gmail API operations
│       └── OpenAIService.php        # OpenAI API integration
├── resources/views/
│   ├── dashboard.blade.php          # Main dashboard
│   └── inbox.blade.php              # Email inbox with AI features
├── routes/web.php                   # Application routes
└── config/services.php              # API service configurations
```

## Security Features

- **OAuth2** - Secure Gmail authentication
- **Session Storage** - Access tokens stored securely
- **CSRF Protection** - All forms protected
- **Input Validation** - All API requests validated

## API Integrations

### Gmail API
- **Scopes**: gmail.readonly, gmail.send, gmail.compose
- **Functions**: Fetch emails, send replies, OAuth2 flow

### OpenAI API
- **Model**: GPT-3.5-turbo
- **Functions**: Generate professional email replies and summaries

## Troubleshooting

### Gmail Connection Issues
1. Verify Google API credentials in `.env`
2. Check OAuth redirect URI matches exactly
3. Ensure Gmail API is enabled in Google Cloud Console

### OpenAI API Issues
1. Verify API key in `.env`
2. Check account has sufficient credits
3. Review API rate limits

### General Issues
1. Clear cache: `php artisan config:clear`
2. Regenerate key: `php artisan key:generate`
3. Check logs: `storage/logs/laravel.log`

## Development Notes

- Uses SQLite for easy local development
- Session-based token storage (suitable for single-user demo)
- Responsive design works on mobile and desktop
- Error handling with user-friendly messages
- Loading states for better UX

## Future Enhancements

- Multi-user support with database token storage
- Email templates and customization
- Advanced AI prompts and context
- Email scheduling and automation
- Analytics and reporting

## Support

For issues or questions:
1. Check the logs in `storage/logs/`
2. Verify all environment variables are set
3. Ensure all API credentials are valid and active

---

**Note**: This application is designed for demonstration and development purposes. For production use, implement proper token storage, error handling, and security measures.