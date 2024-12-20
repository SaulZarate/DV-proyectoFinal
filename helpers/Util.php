<?

class Util
{
    public static function printVar($var, $die = false)
    {
        echo "<pre>";
        print_r($var);
        echo "</pre>";

        if ($die) {
            echo "<br><br>-------------------------- DIE | Print Var -------------------------- <br><br><br>";
            die();
        }
    }

    /**
     * @param int $bytes 
     * @param string $convert_to BK(default) | MB | GB | TB
     */
    public static function convertBytes(int $bytes, string $convert_to = 'KB'): int
    {
        if ($convert_to == 'KB') {
            $value = ($bytes / 1024);
        } elseif ($convert_to == 'MB') {
            $value = ($bytes / 1048576);
        } elseif ($convert_to == 'GB') {
            $value = ($bytes / 1073741824);
        } elseif ($convert_to == 'TB') {
            $value = ($bytes / 1099511627776);
        } else {
            $value = $bytes;
        }
        return $value;
    }

    public static function numberToPrice($number, $onlyInterger = false)
    {
        $result = number_format($number, 2, ",", ".");
        if($onlyInterger) $result = explode(",", $result)[0];
        return $result;
    }

    public static function dateToAge($birthDate)
    {
        $birthDate = date("Y-m-d", strtotime(str_replace("/", "-", $birthDate)));
        [$anio, $mes, $dia] = explode("-", $birthDate);
        
        return (date("md", date("U", mktime(0, 0, 0, $mes, $dia, $anio))) > date("md") ? ((date("Y") - $anio) - 1) : (date("Y") - $anio));
    }

    public static function arrayToObject($data){
        return json_decode(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    public static function numberMonthToMes($number){
        if(is_numeric($number)) $number = strval($number);
        $number = str_pad($number, 2,"0", STR_PAD_LEFT);

        $meses = array(
            "01" => "enero",
            "02" => "febrero",
            "03" => "marzo",
            "04" => "abril",
            "05" => "mayo",
            "06" => "junio",
            "07" => "julio",
            "08" => "agosto",
            "09" => "septiembre",
            "10" => "octubre",
            "11" => "noviembre",
            "12" => "diciembre",
        );

        return $meses[$number];
    }
}
