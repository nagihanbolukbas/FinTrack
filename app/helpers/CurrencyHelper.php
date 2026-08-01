<?php

class CurrencyHelper
{
    public static function getRates()
    {
        $cacheFile = __DIR__ . "/../../cache/exchange.json";

        // Cache varsa ve 24 saatten eski değilse
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
            return json_decode(file_get_contents($cacheFile), true);
        }

        $today = date("Y-m-d");

        // 3 saniye timeout
        $context = stream_context_create([
            "http" => [
                "timeout" => 3
            ]
        ]);

        $json = @file_get_contents(
            "https://api.frankfurter.app/$today?from=TRY&to=USD,EUR",
            false,
            $context
        );

        if ($json !== false) {

            file_put_contents($cacheFile, $json);

            return json_decode($json, true);
        }

        // API çalışmazsa eski cache
        if (file_exists($cacheFile)) {
            return json_decode(file_get_contents($cacheFile), true);
        }

        return [
            "rates" => [
                "USD" => 0,
                "EUR" => 0
            ],
            "date" => date("Y-m-d")
        ];
    }
}