<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'xp', 'level', 'enrollment_no', 'login_code', 'status', 'abc_card_id', 'phone', 'dob', 'gender', 'blood_group', 'aadhar_no', 'guardian_name', 'address', 'role', 'department_id', 'access_level', 'profile_photo', 'profile_photo_data', 'profile_photo_mime', 'digital_signature', 'program', 'year', 'semester', 'class_section', 'parent_student_id', 'parent_1_id', 'parent_2_id', 'parent_3_id', 'parent_4_id', 'hostel_swami_id', 'hostel_room_no'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if (!$user->enrollment_no) {
                // Generate exactly 8 digit numerical enrollment number
                $user->enrollment_no = (string) mt_rand(10000000, 99999999);
            }
            if (!$user->digital_signature) {
                $user->digital_signature = self::generateSignatureSvg($user->name);
            }
        });
    }

    public static function generateSignatureSvg($name)
    {
        $cleanName = preg_replace('/[^a-zA-Z0-9\s.]/', '', $name);
        $cleanName = substr($cleanName, 0, 30);
        $fonts = ['Dancing Script', 'Caveat', 'Sacramento', 'Yellowtail', 'Great Vibes', 'Allura'];
        $index = abs(crc32($cleanName)) % count($fonts);
        $font = $fonts[$index];
        $seed = abs(crc32($cleanName));
        $cp1_y = 35 + ($seed % 10);
        $cp2_y = 40 - (($seed >> 1) % 10);
        
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 50" width="120" height="40">';
        $svg .= '<defs>';
        $svg .= '<style>';
        $svg .= '@import url(\'https://fonts.googleapis.com/css2?family=' . urlencode($font) . '&amp;display=swap\');';
        $svg .= '.sig-txt { font-family: \'' . $font . '\', cursive; font-size: 20px; fill: #1e3a8a; font-style: italic; }';
        $svg .= '</style>';
        $svg .= '</defs>';
        $svg .= '<path d="M 10 ' . $cp1_y . ' Q 80 ' . $cp2_y . ' 150 38" fill="none" stroke="#ea580c" stroke-width="1.5" stroke-linecap="round" opacity="0.65"/>';
        $svg .= '<text x="15" y="32" class="sig-txt">' . e($cleanName) . '</text>';
        $svg .= '</svg>';
        return $svg;
    }

    public function getDigitalSignatureAttribute($value)
    {
        if (empty($value) || strpos($value, '<svg') === false) {
            $svg = self::generateSignatureSvg($this->name);
            $this->attributes['digital_signature'] = $svg;
            if ($this->exists) {
                $this->saveQuietly();
            }
            return $svg;
        }
        return $value;
    }

    public function favorites()
    {
        return $this->belongsToMany(Course::class, 'favorites');
    }

    public function reviews()
    {
        return $this->hasMany(CourseRating::class);
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function child()
    {
        return $this->belongsTo(User::class, 'parent_student_id');
    }

    public function parents()
    {
        return $this->hasMany(User::class, 'parent_student_id');
    }

    public function parent1()
    {
        return $this->belongsTo(User::class, 'parent_1_id');
    }

    public function parent2()
    {
        return $this->belongsTo(User::class, 'parent_2_id');
    }

    public function parent3()
    {
        return $this->belongsTo(User::class, 'parent_3_id');
    }

    public function parent4()
    {
        return $this->belongsTo(User::class, 'parent_4_id');
    }

    public function hostelSwami()
    {
        return $this->belongsTo(User::class, 'hostel_swami_id');
    }

    public function getLinkedParentsAttribute()
    {
        return $this->parents;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
