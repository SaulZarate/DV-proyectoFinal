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
}