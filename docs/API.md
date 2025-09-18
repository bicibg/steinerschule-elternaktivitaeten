# API Documentation

## Edit Token System

The platform uses a token-based system for allowing anonymous editing of bulletin posts. Each bulletin post has a unique edit token that allows the creator to modify or delete their post without authentication.

### Edit Token Flow

1. **Token Generation**: When a bulletin post is created, a unique 64-character token is automatically generated
2. **Token Storage**: The token is stored in the `edit_token` column of the `bulletin_posts` table
3. **Token Usage**: Users access the edit page using the token in the URL query parameter

### Endpoints

#### GET `/pinnwand/{slug}/edit`

Access the edit form for a bulletin post.

**Parameters:**
- `slug` (route parameter): The URL slug of the bulletin post
- `token` (query parameter): The 64-character edit token

**Example:**
```
GET /pinnwand/schulfest-2024-abc123/edit?token=4I3l0U2vYvnLPZffNhAAaV8kisDhntvrBzvb5lPx2lKpNdqnbqIEhPgBj1AJ3Az6
```

**Response:**
- Success (200): Returns the edit form view
- Forbidden (403): Invalid or missing token

**Security:**
- Protected by `VerifyEditToken` middleware
- Token must match exactly with database value

#### PUT `/pinnwand/{slug}`

Update a bulletin post.

**Parameters:**
- `slug` (route parameter): The URL slug of the bulletin post
- `token` (query parameter): The 64-character edit token

**Request Body:**
```json
{
  "title": "Updated Title",
  "description": "Updated description",
  "start_at": "2024-12-01 10:00:00",
  "end_at": "2024-12-01 18:00:00",
  "location": "Steinerschule Bern",
  "contact_name": "Max Mustermann",
  "contact_phone": "+41 76 123 45 67",
  "contact_email": "max@example.com",
  "status": "published",
  "has_forum": true,
  "has_shifts": false
}
```

**Response:**
- Success (302): Redirects to edit page with success message
- Validation Error (422): Returns validation errors
- Forbidden (403): Invalid or missing token

**Security:**
- Protected by `VerifyEditToken` middleware
- Validates all input through `UpdateBulletinRequest` form request

### Implementation Details

#### Middleware: `VerifyEditToken`

Location: `app/Http/Middleware/VerifyEditToken.php`

The middleware:
1. Extracts slug and token from request
2. Verifies token exists and is not empty
3. Looks up bulletin post by slug
4. Compares provided token with stored token
5. Returns 403 if verification fails
6. Attaches bulletin post to request if successful

#### Token Generation

Tokens are generated using Laravel's `Str::random(64)` method in the BulletinPost model's `booted()` method:

```php
protected static function booted()
{
    static::creating(function ($bulletinPost) {
        if (empty($bulletinPost->edit_token)) {
            $bulletinPost->edit_token = Str::random(64);
        }
    });
}
```

### Security Considerations

1. **Token Length**: 64 characters provides sufficient entropy against brute force attacks
2. **HTTPS Required**: Tokens should only be transmitted over HTTPS in production
3. **No Token Regeneration**: Tokens cannot be regenerated once created
4. **No Token Recovery**: Lost tokens cannot be recovered - contact admin required
5. **Token Visibility**: Tokens are only visible to the post creator at creation time

### Best Practices

1. **Share Carefully**: Only share edit links with trusted individuals
2. **Use HTTPS**: Always use HTTPS links when sharing edit URLs
3. **Monitor Access**: Check activity logs for unauthorized access attempts
4. **Rotate if Compromised**: Contact admin to archive and recreate post if token is compromised

### Error Messages

All error messages are in German for consistency:

- Missing token: "Zugriff verweigert. Kein gültiger Bearbeitungstoken."
- Invalid token: "Zugriff verweigert. Ungültiger Bearbeitungstoken."
- Post not found: Standard 404 page

### Future Improvements

- [ ] Token expiration after X days
- [ ] Token usage tracking/logging
- [ ] Email notification on edit
- [ ] Optional password protection in addition to token
- [ ] Token regeneration capability for admins