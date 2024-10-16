<?

class Util{
    public static function printVar($var, $die = false){
        echo "<pre>";
        print_r($var);
        echo "</pre>";

        if($die){
            echo "<br><br>-------------------------- DIE | Print Var -------------------------- <br><br><br>";
            die();
        }
    }

    /**
     * @param int $bytes 
     * @param string $convert_to BK(default) | MB | GB | TB
     */
    public static function convertBytes(int $bytes, string $convert_to = 'KB'): int{
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
}