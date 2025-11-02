<?php

declare(strict_types = 1);

/*
 * This file is part of the LSP API and Panels projects
 *
 * Copyright (c) >= 2023 LSP
 *
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 * Please follow OOP, SOLID and linux philosophy in development and becarefull about anti-patterns
 *
 * @CTO Mehrdad Dadkhah <dadkhah.ir@gmail.com>
 */

namespace App\Core\User\Entities;

use App\Contracts\Model\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int         $id
 * @property int|null    $province_id
 * @property string      $name
 * @property string|null $name_en
 * @property float|null  $latitude
 * @property float|null  $longitude
 *
 * Relations:
 * @property Province    $province
 */
class City extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'location_cities';

    protected $fillable = [
        'province_id',
        'name',
        'name_en',
        'latitude',
        'longitude',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
