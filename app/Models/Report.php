<?php

namespace App\Models;

use App\Observers\ReportObserver;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $url
 * @property string $content_type
 * @property string $status
 * @property int $header_size
 * @property int $request_size
 * @property int $redirect_count
 * @property int $http_version
 * @property int $appconnect_time
 * @property int $connect_time
 * @property int $namelookup_time
 * @property int $pretransfer_time
 * @property int $redirect_time
 * @property int $starttransfer_time
 * @property int $total_time
 * @property string $check_id
 * @property CarbonInterface $started_at
 * @property CarbonInterface $finished_at
 * @property null|CarbonInterface $created_at
 * @property null|CarbonInterface $updated_at
 * @property Check $check
 */

#[ObservedBy(classes: ReportObserver::class)]
final class Report extends Model
{
    use HasFactory;
    use HasUlids;

    /**
     * @var array<int,string>
     */
    protected $fillable = [
        'url',
        'content_type',
        'status',
        'header_size',
        'request_size',
        'redirect_count',
        'http_version',
        'appconnect_time',
        'connect_time',
        'namelookup_time',
        'pretransfer_time',
        'redirect_time',
        'starttransfer_time',
        'total_time',
        'check_id',
        'started_at',
        'finished_at',
    ];

    /** @return BelongsTo<Check> */
    public function check(): BelongsTo
    {
        return $this->belongsTo(
            related: Check::class,
            foreignKey: 'check_id'
        );
    }

    /**
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'integer',
            'header_size' => 'integer',
            'request_size' => 'integer',
            'redirect_count' => 'integer',
            'http_version' => 'integer',
            'appconnect_time' => 'integer',
            'connect_time' => 'integer',
            'namelookup_time' => 'integer',
            'pretransfer_time' => 'integer',
            'redirect_time' => 'integer',
            'starttransfer_time' => 'integer',
            'total_time' => 'integer',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }
}
