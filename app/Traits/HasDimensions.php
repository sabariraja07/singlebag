<?php

namespace App\Traits;

use Cartalyst\Converter\Laravel\Facades\Converter;

trait HasDimensions
{
    /**
     * Method when trait is booted.
     *
     * @return void
     */
    public static function bootHasDimensions()
    {
        self::retrieved(function ($model) {
            $model->mergeCasts([
                'length' => 'float',
                'width' => 'float',
                'height' => 'float',
                'volume' => 'float',
                'weight' => 'float',
            ]);
        });
    }

    /**
     * Getter for the length attribute.
     *
     * @return \Cartalyst\Converter\Converter
     */
    public function getLengthsAttribute()
    {
        $unit = $this->package_unit ?: 'mm';

        return Converter::from("length.{$unit}")->value($this->length ?: 0);
    }

    /**
     * Getter for the width attribute.
     *
     * @return \Cartalyst\Converter\Converter
     */
    public function getWidthsAttribute()
    {
        $unit = $this->package_unit ?: 'mm';

        return Converter::from("length.{$unit}")->value($this->width ?: 0);
    }

    /**
     * Getter for height attribute.
     *
     * @return \Cartalyst\Converter\Converter
     */
    public function getHeightsAttribute()
    {
        $unit = $this->package_unit ?: 'mm';

        return Converter::from("length.{$unit}")->value($this->height ?: 0);
    }

    /**
     * Getter for weight attribute.
     *
     * @return \Cartalyst\Converter\Converter
     */
    public function getWeightsAttribute()
    {
        $unit = $this->weight_unit ?: 'g';

        return Converter::from("weight.{$unit}")->value($this->weight ?: 0);
    }

    /**
     * Getter for the volume attribute.
     *
     * @return \Cartalyst\Converter\Converter
     */
    public function getVolumesAttribute()
    {
        if ($this->volume && $this->volume_unit) {
            return Converter::from("volume.{$this->volume_unit}")
                ->to("volume.{$this->volume_unit}")->value($this->volume);
        }

        $length = $this->lengths->to('length.cm')->convert()->getValue();
        $width = $this->widths->to('length.cm')->convert()->getValue();
        $height = $this->heights->to('length.cm')->convert()->getValue();

        return Converter::from('volume.ml')->to('volume.l')->value($length * $width * $height)->format();
    }
}
