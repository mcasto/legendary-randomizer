# API Documentation

## Entities Endpoint

### GET `/api/entities`

Returns all game entities (heroes, masterminds, schemes, villains, henchmen) that belong to the sets owned by the authenticated user.

**Authentication Required:** Yes (Sanctum token)

**Request Headers:**
```
Authorization: Bearer {your-token}
Content-Type: application/json
```

**Response Format:**
```json
{
  "status": "success",
  "data": {
    "heroes": [
      {
        "id": 1,
        "name": "Spider-Man",
        "set": "base",
        "colors": [
          {
            "value": "red",
            "label": "Red", 
            "icon": "red.svg"
          }
        ],
        "teams": [
          {
            "value": "avengers",
            "label": "Avengers",
            "icon": "avengers.svg"
          }
        ]
      }
    ],
    "masterminds": [
      {
        "id": 1,
        "name": "Red Skull",
        "set": "base"
      }
    ],
    "schemes": [
      {
        "id": 1,
        "name": "The Legacy Virus",
        "set": "base"
      }
    ],
    "villains": [
      {
        "id": 1,
        "name": "Chitauri",
        "set": "base"
      }
    ],
    "henchmen": [
      {
        "id": 1,
        "name": "Hand Ninjas",
        "set": "base"
      }
    ]
  }
}
```

**Error Response:**
```json
{
  "status": "error",
  "message": "Error description"
}
```

**Usage Example (JavaScript/Axios):**
```javascript
// Assuming you have the token stored
const token = localStorage.getItem('auth_token');

const response = await axios.get('/api/entities', {
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json'
  }
});

const { heroes, masterminds, schemes, villains, henchmen } = response.data.data;
```

**Notes:**
- Only entities from sets that the user owns will be returned
- Heroes include additional `colors` and `teams` arrays with their affiliations
- Schemes, masterminds, villains, and henchmen include `id`, `name`, and `set` properties
- If the user has no sets, empty arrays will be returned for all entity types
