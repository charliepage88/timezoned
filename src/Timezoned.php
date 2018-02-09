<?php

/**
 * @author Ryan Weber <ryan@picoprime.com>
 */

namespace RyanWeber\Mutators;

use Carbon\Carbon;
use Illuminate\Support\Str;

trait Timezoned
{
	/**
	 * Override original hasGetMutator method to also
	 * check if we should apply timezone to the field.
	 *
	 * @param $key
	 *
	 * @return bool
	 */
	public function hasGetMutator($key)
	{
		return parent::hasGetMutator($key) || $this->shouldApplyTimezone($key);
	}

	/**
	 * Override original setAttribute method to process
	 * field as normal and then apply timezone if required.
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return $this
	 */
	public function setAttribute($key, $value)
	{
		parent::setAttribute($key, $value);

		if ($this->shouldApplyTimezone($key)) {
			$this->attributes[$key] = $this->setAppTimezone($value);
		}

		return $this;
	}

	/**
	 * Override original mutateAttribute to translate
	 * timestamp from application's (usually UTC) to
	 * user's timezone.
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return mixed
	 */
	protected function mutateAttribute($key, $value)
	{
		if ($this->shouldApplyTimezone($key)) {
			$value = $this->setUserTimezone($value);
		}

		if (method_exists($this, 'get'.Str::studly($key).'Attribute')) {
			$value = $this->{'get' . Str::studly($key) . 'Attribute'}($value);
		}

		return $value;
	}

	/**
	 * Check if the field is listed in $dates or
	 * $timezoned properties on the model.
	 *
	 * @param $key
	 *
	 * @return bool
	 */
	protected function shouldApplyTimezone($key)
	{
		return in_array($key, $this->getTimezonedDates());
	}

	/**
	 * Get array of date field names from model's
	 * $timezoned property or original $dates property.
	 *
	 * @return array
	 */
	protected function getTimezonedDates()
	{
		return $this->timezoned ?? $this->getDates();
	}

	/**
	 * Apply timezone to the value.
	 *
	 * @param mixed $value
	 * @param string $fromTz
	 * @param string $toTz
	 *
	 * @return \Carbon\Carbon
	 */
	protected function applyTimezone($value, $fromTz, $toTz)
	{
        if (!$value) {
            return $value;
        }

		return Carbon::parse($value, $fromTz)->timezone($toTz);
	}

	/**
	 * Change user's timezone into application's.
	 *
	 * @param mixed $value
	 *
	 * @return \Carbon\Carbon
	 */
	protected function setAppTimezone($value)
	{
		return $this->applyTimezone(
			$value,
			call_user_func(config('timezoned.user_timezone')),
			config('app.timezone')
		);
	}

	/**
	 * Change application's timezone into user's.
	 *
	 * @param mixed $value
	 *
	 * @return \Carbon\Carbon
	 */
	protected function setUserTimezone($value)
	{
		return $this->applyTimezone(
			$value,
			config('app.timezone'),
			call_user_func(config('timezoned.user_timezone'))
		);
	}
}