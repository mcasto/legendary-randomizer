# Remote Database Update System - Multiple Endpoints Approach

## Overview
This document outlines the plan to modify the existing `legendary:update-database` Artisan command to support remote database updates using multiple JSON endpoints instead of direct local database updates.

## Current System
The existing command performs these steps locally:
1. Clone master-strike repository
2. Extract and process TypeScript card definitions  
3. Create SQLite database with duplicate entity handling
4. Analyze differences and update local MariaDB
5. Verify results and cleanup

## Proposed Remote System

### Architecture
**Local Command (Development)** → **Multiple API Endpoints (Production)**

### Data Flow
```
Local Environment:
1. Generate processed JSON data for each entity type
2. Upload JSON files to respective production endpoints
3. Monitor progress and handle responses
4. Cleanup local temporary files on success

Production Environment:
1. Receive JSON data per entity type
2. Backup current database state
3. Process each entity type atomically
4. Return success/error responses
5. Rollback on any failures
```

### API Endpoints Structure
```
POST /api/legendary/update-heroes
POST /api/legendary/update-masterminds  
POST /api/legendary/update-villains
POST /api/legendary/update-henchmens
POST /api/legendary/update-schemes
```

Each endpoint will:
- Accept JSON array of entities for that type
- Validate JSON structure and authentication
- Create database backup point
- Process entities with duplicate handling
- Return detailed success/error response
- Support rollback on failures

### JSON Data Structure
Each endpoint receives an array of entities:
```json
[
  {
    "name": "Howard the Duck",
    "set": "Fantastic Four",
    "id": "howard_the_duck",
    // ... other entity-specific fields
  },
  {
    "name": "Howard the Duck", 
    "set": "Marvel Studios' What If...?",
    "id": "howard_the_duck",
    // ... duplicate with different set
  }
]
```

### Modified Artisan Command Flow
1. **Generate Data** (Steps 1-3 remain the same)
   - Clone repository
   - Extract and process data
   - Create SQLite with duplicates

2. **Export to JSON** (New step)
   - Query SQLite database
   - Generate separate JSON files for each entity type
   - Include all entity data with proper duplicate handling

3. **Upload to Production** (Replaces Step 4)
   - Authenticate with production API
   - Upload heroes.json to `/api/legendary/update-heroes`
   - Upload masterminds.json to `/api/legendary/update-masterminds`
   - Continue for all entity types
   - Show progress for each upload

4. **Handle Responses** (Modified Step 5)
   - Collect success/error responses from each endpoint
   - Display detailed results per entity type
   - Handle partial failures gracefully

5. **Cleanup** (Same as current)
   - Remove temporary files on full success
   - Preserve files for debugging on errors

### Advantages of Multiple Endpoints

**Granular Control:**
- Each entity type processed independently
- Partial success possible (heroes succeed, masterminds fail)
- Clear error isolation and debugging

**Better Progress Tracking:**
- Show progress as each entity type completes
- User sees incremental updates
- Clear indication of which step failed

**Atomic Operations:**
- Each entity type update is atomic
- Database backup/restore per entity type
- Safer rollback handling

**Easier Maintenance:**
- Separate validation logic per entity type
- Independent error handling
- Cleaner code organization

**Network Resilience:**
- Retry individual entity types on failure
- Smaller payload sizes
- Better timeout handling

### Error Handling Strategy

**Local Command Errors:**
- Network failures: Retry with exponential backoff
- Authentication failures: Clear error message and exit
- Partial failures: Report which entity types failed
- Preserve temporary files for debugging

**Production API Errors:**
- Validation errors: Return detailed field-level errors
- Database errors: Rollback and return error details
- System errors: Log and return generic error message
- Backup failures: Prevent update and return error

### Authentication Strategy

**User-Based Authentication Flow:**
1. Create dedicated user: `updates@legendary-randomizer.castoware.com`
2. Store credentials only in local `.env` file
3. Command flow: Login → Get token → Upload data → Logout → Cleanup

**Authentication Process:**
```
Local Environment (.env):
LEGENDARY_API_BASE_URL=https://legendary-randomizer.castoware.com/api
LEGENDARY_UPDATE_EMAIL=updates@legendary-randomizer.castoware.com
LEGENDARY_UPDATE_PASSWORD=secret-password-here

Command Flow:
1. POST /api/auth/login → Get Sanctum token
2. POST /api/legendary/update-heroes (Bearer token)
3. POST /api/legendary/update-masterminds (Bearer token)
4. Continue for all entity types...
5. POST /api/auth/logout → Cleanup token
6. Local file cleanup
```

**Security Advantages:**
- Credentials only in local environment
- Uses existing Sanctum authentication
- Temporary tokens (destroyed after use)
- Clear audit trail via dedicated user account
- Easy to revoke (disable user account)

### Security Considerations

**Authentication:**
- Dedicated user account for database updates
- Standard Sanctum login/logout flow
- Rate limiting per authenticated user
- Request size limits per endpoint

**Validation:**
- JSON schema validation for each entity type
- Sanitization of entity names and data
- Check for malicious content

**Logging:**
- Detailed logs of all update attempts
- Track updates user in authentication logs
- Log all database changes

### Configuration Requirements

**Local Environment (.env):**
```env
LEGENDARY_API_BASE_URL=https://legendary-randomizer.castoware.com/api
LEGENDARY_UPDATE_EMAIL=updates@legendary-randomizer.castoware.com
LEGENDARY_UPDATE_PASSWORD=secret-password-here
```

**Production Environment:**
- Create dedicated user account in database
- Configure Sanctum for API authentication  
- Database backup strategy
- Rollback time limits
- Maximum entities per request
- Logging configuration

### Implementation Phases

**Phase 1: JSON Export**
- Modify existing command to export JSON files
- Add SQLite to JSON conversion logic
- Test JSON structure matches expected format

**Phase 2: API Endpoints**
- Create Laravel API routes and controllers
- Implement authentication and validation
- Add database backup/restore logic

**Phase 3: Remote Upload**
- Add HTTP client to Artisan command
- Implement progress tracking
- Add error handling and retry logic

**Phase 4: Testing & Refinement**
- End-to-end testing with real data
- Performance optimization
- Error handling improvements

### Rollback Strategy

**Per-Entity-Type Rollback:**
- Each endpoint creates backup before processing
- Rollback on any entity processing failure
- Independent rollback per entity type

**Full System Rollback:**
- If any entity type fails, option to rollback all
- Maintain backup of entire database state
- Two-phase commit approach possible

### Monitoring & Logging

**Local Command Logging:**
- Log all API requests and responses
- Track upload progress and timing
- Record any retry attempts

**Production API Logging:**
- Log all incoming requests with user info
- Track database changes per entity type
- Monitor backup/restore operations
- Alert on any failures

This approach provides a robust, maintainable solution for remote database updates while preserving all the duplicate entity handling logic that has been developed.
