<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NelcXapiStatement extends Model
{
    protected $table = 'nelc_xapi_statements';

    protected $fillable = [
        'user_id',
        'webinar_id',
        'verb',
        'object_type',
        'object_id',
        'response_status',
        'response_body',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    // xAPI Verbs
    const VERB_REGISTERED = 'registered';
    const VERB_INITIALIZED = 'initialized';
    const VERB_WATCHED = 'watched';
    const VERB_COMPLETED = 'completed';
    const VERB_ATTENDED = 'attended';
    const VERB_ATTEMPTED = 'attempted';
    const VERB_PROGRESSED = 'progressed';
    const VERB_RATED = 'rated';
    const VERB_EARNED = 'earned';

    // Object Types
    const TYPE_COURSE = 'course';
    const TYPE_LESSON = 'lesson';
    const TYPE_VIDEO = 'video';
    const TYPE_MODULE = 'module';
    const TYPE_QUIZ = 'quiz';
    const TYPE_CERTIFICATE = 'certificate';
    const TYPE_VIRTUAL_CLASSROOM = 'virtual_classroom';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\Webinar', 'webinar_id');
    }

    /**
     * Check if a statement with the same verb and object_id already exists for a user.
     */
    public static function isDuplicate(int $userId, string $verb, string $objectId): bool
    {
        return self::where('user_id', $userId)
            ->where('verb', $verb)
            ->where('object_id', $objectId)
            ->exists();
    }
}
