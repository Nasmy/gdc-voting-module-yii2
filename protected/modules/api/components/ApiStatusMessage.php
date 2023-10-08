<?php
namespace app\modules\api\components;

use yii\base\Component;

class ApiStatusMessage extends Component
{
    // Common error codes
    const SUCCESS = 'SUCCESS';
    const FAILED = 'FAILED';

    const INVALID_TOKEN = 'INVALID_TOKEN';

    const AUTH_FAILED = 'AUTH_FAILED';

    const MISSING_MANDATORY_FIELD = 'MISSING_MANDATORY_FIELD';
    const VALIDATION_FAILED = 'VALIDATION_FAILED';

    const RECORD_EXISTS = 'RECORD_EXISTS';
    const RECORD_NOT_EXISTS = 'RECORD_NOT_EXISTS';

    const INVALID_EMAIL = 'INVALID_EMAIL';

    const EMAIL_EXISTS = 'EMAIL_EXISTS';
    const MOBILE_NO_EXISTS = 'MOBILE_NO_EXISTS';
    const SOCIAL_ACCOUNT_EXISTS = 'SOCIAL_ACCOUNT_EXISTS';

    const INVALID_OLD_PASSWORD = 'INVALID_OLD_PASSWORD';

    // Poll
    const MISSING_MANDATORY_LIMIT_FIELD = 'MISSING_MANDATORY_LIMIT_FIELD';

    // Page
    const MISSING_PAGE_TYPE_DEPEND_FIELD = 'MISSING_PAGE_TYPE_DEPEND_FIELD';

    // Rate
    const MISSING_RATE_TYPE_DEPEND_FIELD = 'MISSING_RATE_TYPE_DEPEND_FIELD';

    // File upload
    const FILE_UPLOAD_SIZE_EXCEED = 'FILE_UPLOAD_SIZE_EXCEED';
    const FILE_UPLOAD_PARTIAL = 'FILE_UPLOAD_PARTIAL';
    const FILE_UPLOAD_NO_FILE = 'FILE_UPLOAD_NO_FILE';
    const FILE_UPLOAD_NO_TEMP_FOLDER = 'FILE_UPLOAD_NO_TEMP_FOLDER';
    const FILE_UPLOAD_WRITE_ERROR = 'FILE_UPLOAD_WRITE_ERROR';
    const FILE_UPLOAD_EXTENSION_ERROR = 'FILE_UPLOAD_EXTENSION_ERROR';
    const FILE_UPLOAD_UNKNOWN_ERROR = 'FILE_UPLOAD_UNKNOWN_ERROR';

    // Follower
    const ALL_READY_FOLLOWING = 'ALL_READY_FOLLOWING';
    const ALL_READY_FOLLOWER = 'ALL_READY_FOLLOWER';
}
