<?php

namespace App\Traits\Models;

use DateTime;
use App\Models\Channel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait HasChannels
{
    public static function bootHasChannels()
    {
        static::created(function (Model $model) {
            $channels = Channel::get()->mapWithKeys(function ($channel) {
                return [
                    $channel->id => [
                        'enabled' => false,
                        'starts_at' => null,
                        'ends_at' => null,
                    ],
                ];
            });

            $model->channels()->sync($channels);
        });
    }

    public function channels()
    {
        return $this->belongsToMany(
            Channel::class,
            'channelables',
            "product_id",
            "channel_id"
        )->withPivot([
            'enabled',
            'starts_at',
            'ends_at',
        ])->withTimestamps();
    }

    public function scheduleChannel($channel, DateTime $startsAt = null, DateTime $endsAt = null)
    {
        if ($channel instanceof Model) {
            $channel = collect([$channel]);
        }

        DB::transaction(function () use ($channel, $startsAt, $endsAt) {
            $this->channels()->sync(
                $channel->mapWithKeys(function ($channel) use ($startsAt, $endsAt) {
                    return [
                        $channel->id => [
                            'enabled' => true,
                            'starts_at' => $startsAt,
                            'ends_at' => $endsAt,
                        ],
                    ];
                })
            );
        });
    }

    public function activeChannels()
    {
        return $this->channels()->where(function ($query) {
            $query->whereNull('starts_at')
                ->orWhere('starts_at', '<=', now());
        })->where(function ($query) {
            $query->whereNull('ends_at')
                ->orWhere('ends_at', '>=', now());
        })->whereEnabled(true);
    }

    public function scopeChannel($query, Channel|iterable $channel = null, DateTime $startsAt = null, DateTime $endsAt = null)
    {
        if (blank($channel)) {
            return $query;
        }

        if (!$startsAt) {
            $startsAt = now();
        }

        if (!$endsAt) {
            $endsAt = now()->addSecond();
        }

        $channelIds = collect();

        if (is_a($channel, Channel::class)) {
            $channelIds = collect([$channel->id]);
        }

        if (is_a($channel, Collection::class)) {
            $channelIds = $channel->pluck('id');
        }

        if (is_array($channel)) {
            $channelIds = collect($channel)->pluck('id');
        }

        return $query->whereHas('channels', function ($relation) use ($channelIds, $startsAt, $endsAt) {
            $relation->whereIn(
                $this->channels()->getTable() . '.channel_id',
                $channelIds
            )->where(function ($query) use ($startsAt) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $startsAt);
            })->where(function ($query) use ($endsAt) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', $endsAt);
            })->whereEnabled(true);
        });
    }
}
