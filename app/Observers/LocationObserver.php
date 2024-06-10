<?php

namespace App\Observers;

use App\Models\Location;
use Illuminate\Support\Facades\DB;

class LocationObserver
{
    /**
     * Handle the Location "created" event.
     *
     * @return void
     */
    public function created(Location $location)
    {
        $this->ensureOnlyOneDefault($location);
    }

    /**
     * Handle the Location "updated" event.
     *
     * @return void
     */
    public function updated(Location $location)
    {
        $this->ensureOnlyOneDefault($location);
    }

    /**
     * Handle the Location "deleted" event.
     *
     * @return void
     */
    public function deleting(Location $location)
    {
        DB::transaction(function () use ($location) {
            $location->urls()->delete();
        });
    }

    /**
     * Handle the Location "forceDeleted" event.
     *
     * @return void
     */
    public function forceDeleted(Location $location)
    {
        //
    }

    /**
     * Ensures that only one default location exists.
     *
     * @param  \App\Models\Location  $savedLocation  The location that was just saved.
     */
    protected function ensureOnlyOneDefault(Location $savedLocation): void
    {
        // Wrap here so we avoid a query if it's not been set to default.
        if ($savedLocation->default) {
            Location::withoutEvents(function () use ($savedLocation) {
                Location::whereShopId(current_shop_id())->whereDefault(true)->where('id', '!=', $savedLocation->id)->update([
                    'default' => false,
                ]);
            });
        }
    }
}
