<?php
namespace App\Services;

use Illuminate\Support\Collection;
use Carbon\Carbon;

class BladeUtilService
{

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Convert collection to options required by select, checkbox or radio
     *
     * @param Collection $collection
     * @param string $idKey
     * @param string $textKey
     * @return assoicated array
     */
    public function collectionToOptions(Collection $collection, $idKey = 'id', $textKey = 'name')
    {
        $result = [];
        if (! empty($collection)) {
            $collection->each(function ($item) use (&$result, $idKey, $textKey) {
                $result[$item->{$idKey}] = $item->{$textKey};
            });
        }

        return $result;
    }

    /**
     * Convert associated array to source data required by select 2
     */
    public function arrayToSource($data)
    {
        $result = [];
        foreach ($data as $k => $v) {
            $result[] = [
                'id' => $k,
                'text' => $v
            ];
        }

        return $result;
    }

    /**
     * Convert collection to source data required by select 2
     *
     * @param Collection $collection
     * @param string $idKey
     * @param string $textKey
     * @return array
     */
    public function collectionToSource(Collection $collection, $idKey, $textKey)
    {
        $result = [];
        if (! empty($collection)) {
            $result = $collection->map(function ($item) use ($idKey, $textKey) {
                return [
                    'id' => $item[$idKey],
                    'text' => $item[$textKey]
                ];
            })->toArray();
        }

        return $result;
    }

    /**
     * Get one column from collection
     *
     * @param Collection $collection
     * @param string $columnKey
     * @return array
     */
    public function columnOfCollection(Collection $collection, $columnKey)
    {
        $result = [];
        if (! empty($collection)) {
            $result = $collection->pluck($columnKey)->toArray();
        }

        return $result;
    }

    public function requestStringToIdArray($string, $delimiter = ',')
    {
        $result = [];

        if (! empty($string)) {
            $result = explode($delimiter, $string);
            // calibrate date in format like ",1,2,3" to indexed array
            $result = array_values(array_filter($result, function ($item) {
                return ($item > 0);
            }));
        }

        return $result;
    }

    public function timestamp2DateString($timestamp, $format = 'd/m/Y')
    {
        $dataString = NULL;

        if (! empty($timestamp)) {
            try {
                $dataString = Carbon::createFromTimestamp($timestamp)->format($format);
            } catch (\Exception $e) {
                // do something
            }
        }

        return $dataString;
    }

    public function dateString2Timestamp($dataString, $format = 'd/m/Y', $withTime = false)
    {
        $timestamp = NULL;

        if (! empty($dataString)) {
            try {
                $carbon = Carbon::createFromFormat($format, $dataString);
                if (! $withTime) {
                    $carbon->hour = 0;
                    $carbon->minute = 0;
                    $carbon->second = 0;
                }
                $timestamp = $carbon->getTimestamp();
            } catch (\Exception $e) {
                // do something
            }
        }

        return $timestamp;
    }

    public function btcFormat($btc) {
        $number = number_format(doubleval($btc), 8);

        $result = rtrim($number, "0");
        $result = rtrim($result, ".");

        return $result;
    }
}