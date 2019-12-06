<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25.11.16
 * Time: 16:21
 */

namespace app\traits;


/**
 * Class ConsoleTrait
 * @package app\traits
 *
 * Class for common console app usages
 */
class ConsoleTrait
{

    public static function showProgress($done, $total, $size=30) {

        static $start_time;

        // if we go over our bound, just ignore it
        if($done > $total) return;

        if(empty($start_time)) $start_time=time();
        $now = time();

        $perc=(double)($done/$total);

        $bar=floor($perc*$size);

        $status_bar="\r[";
        $status_bar.=str_repeat("=", $bar);
        if($bar<$size){
            $status_bar.=">";
            $status_bar.=str_repeat(" ", $size-$bar);
        } else {
            $status_bar.="=";
        }

        $disp=number_format($perc*100, 0);

        $status_bar.="] $disp%  $done/$total";

        $rate = ($now-$start_time)/$done;
        $left = $total - $done;
        $eta = round($rate * $left, 2);

        $elapsed = $now - $start_time;

        $status_bar.= " remaining: ".number_format($eta)." sec.  elapsed: ".number_format($elapsed)." sec.";

        echo "$status_bar  ";

        flush();

        // when done, send a newline
        if($done == $total) {
            echo "\n";
        }

    }

    /**
     * @param $csvName
     * @param $headers
     * @param $data
     * @param string $delimiter
     */
    public static function writeToCsv($csvName, $headers, $data,$delimiter = ';'){

        $csv =  fopen($csvName.'.csv','w+');

        fputcsv($csv, $headers, $delimiter);

        foreach ($data as $item) {
            fputcsv($csv, $item, $delimiter);
        }

        fclose($csv);
    }

    /**
    /**
     * @param $csvName string file name (without .csv part)
     * @param $delimiter string
     * @param $lineLimit integer
     *
     * @return  array | bool false if file not found
     */
    public static function readFromCsv($csvName, $lineLimit =1000, $delimiter = ';'){

        $result = [];

        if (($handle = fopen($csvName.'.csv', "r")) !== FALSE) {

            while (($data = fgetcsv($handle, $lineLimit, $delimiter)) !== FALSE) {

                $result[]  = $data;
            }
            fclose($handle);
        }

        return $result;
    }
}