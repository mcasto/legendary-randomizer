<?php

namespace Database\Seeders;

class GetSeedData
{
    public static function pull($table)
    {
        $rawSeedData = file_get_contents(__DIR__ . '/seed-data.json');
        $seedData = json_decode($rawSeedData, true);
        $recs = $seedData[$table];
        $data = array_map(function ($rec) {
            return json_decode($rec['ms'], true);
        }, $recs);
        $data = array_filter($data, function ($rec) {
            return $rec['id'] > -1;
        });

        return $data;
    }

    public static function defaultSetups()
    {
        $rawSeedData = file_get_contents(__DIR__ . '/seed-data.json');
        $seedData = json_decode($rawSeedData, true);
        $recs = $seedData['defaultSetups'];

        return $recs;
    }
}
