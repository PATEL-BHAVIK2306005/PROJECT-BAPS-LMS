<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';
    protected $fillable = ['name', 'role', 'department_id', 'unique_code', 'email', 'phone', 'password', 'access_level', 'profile_photo', 'profile_photo_data', 'profile_photo_mime', 'positions', 'exam_role', 'supervisor_id', 'digital_signature', 'status'];

    protected $casts = [
        'positions' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($staff) {
            if (!$staff->digital_signature) {
                $staff->digital_signature = self::generateSignatureSvg($staff->name);
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

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Staff::class, 'supervisor_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Staff::class, 'supervisor_id');
    }
}
