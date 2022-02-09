<?php
namespace Avido\Smtpeter\Support;

class Str
{
    /** @var array */
    protected static $studlyCache = [];

    public static function studly(string $value): string
    {
        $key = $value;

        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }

        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return static::$studlyCache[$key] = str_replace(' ', '', $value);
    }

    public static function upper(string $value): string
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public static function httpParseHeaders($raw)
    {
        $k = '';
        $out = [];

        $exp = explode("\n", $raw);

        foreach ($exp as $v) {
            $exp = explode(':', $v, 2);

            if (isset($exp[1])) {
                /** Сonvert to 'Word-Word' */
                $k = strtolower($exp[0]);
                $k = preg_replace_callback('/\b[a-z]/', function ($m) {
                    return strtoupper($m[0]);
                }, $k);

                $value = trim($exp[1]);

                if (!isset($out[$k])) {
                    $out[$k] = [];
                    /** for in_array */
                }
                if (!in_array($value, $out[$k])) {
                    $out[$k][] = $value;
                    /** New element in array */
                }
            } else {
                $value = trim($exp[0]);

                if (!empty($exp[0][0]) && $exp[0][0] == "\t") {
                    /** Index last element */
                    end($out[$k]);
                    $i = key($out[$k]);

                    $out[$k][$i] .= "\r\n\t" . $value;
                } elseif (!$k) {
                    $out[0] = $value;
                }
            }
        }

        /** Array has only once element - convert in string */
        $out = array_map(function ($v) {
            return count($v) < 2 ? $v[0] : $v;
        }, $out);

        return $out;
    }
}
