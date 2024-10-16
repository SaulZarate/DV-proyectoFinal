<?

/* ------------------------- */
/*          DATABASE         */
/* ------------------------- */
define("DB_HOST", "localhost");
define("DB_PORT", "3306");
define("DB_NAME", "proyecto_final");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");

try {
    $connectDB = new PDO(
        'mysql:host=' . DB_HOST . '; port=' . DB_PORT . '; dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USERNAME,
        DB_PASSWORD
    );
    $connectDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection error " . $e->getMessage();
    exit;
}

class DB{

    public static function getOne($query){
        GLOBAL $connectDB;
        
        $stmt = $connectDB->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function getAll($query){
        GLOBAL $connectDB;
        
        $stmt = $connectDB->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    
    /**
     * Selector generico
     * 
     * @param string $table Nombre de la tabla
     * @param string $columns Columnas a obtener
     * @param string $conditions Columnas a obtener
     * 
     * @return array<object>|false
     */
    public static function select($table, $columns = "*", $conditions = ""){
        GLOBAL $connectDB;
        
        $query = "SELECT $columns FROM $table";
        if (!empty($conditions)) $query .= " WHERE $conditions";

        $stmt = $connectDB->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Inserta un registro
     * 
     * @return int|false
     */
    public static function insert($table, $data){
        GLOBAL $connectDB;
        
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $connectDB->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute() ? $connectDB->lastInsertId() : false;
    }

    /**
     * Inserta multiples registros en una tabla
     * @param string $table Nombre de la tabla
     * @param array<string> $columns ["nombre", "apellido", ....]
     * @param array<array<string>> $rows [["rick", "sanchez", ....], ...]
     * @return bool
     */
    public static function insertMult($table, $columns, $rows) {
        GLOBAL $connectDB;

        // Prepara la consulta SQL
        $placeholders = '(' . implode(',', array_fill(0, count($columns), '?')) . ')';
        $query = "INSERT INTO $table (" . implode(',', $columns) . ") VALUES " . implode(',', array_fill(0, count($rows), $placeholders));
        
        try {
            $stmt = $connectDB->prepare($query);

            // Aplana el array de filas (ya que PDO espera un array plano de valores)
            $flatValues = [];
            foreach ($rows as $row) {
                $flatValues = array_merge($flatValues, array_values($row));
            }

            // Ejecuta la consulta
            $stmt->execute($flatValues);

            return true;
        } catch (PDOException $e) {
            /* echo "Error: " . $e->getMessage(); */
            return false;
        }
    }

    public static function update($table, $data, $conditions = ""){
        GLOBAL $connectDB;
        
        $setClause = "";
        foreach ($data as $key => $value) {
            $setClause .= "$key = :$key, ";
        }
        $setClause = rtrim($setClause, ", ");

        $query = "UPDATE $table SET $setClause";
        if($conditions) $query .= " WHERE {$conditions}";

        $stmt = $connectDB->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    /** 
     * Inserta/actualiza un registro
     * 
     * @return int devuelve el pk del registro
     */
    public static function save($data, $ignore = array()){

        $originDate = $data;

        unset($data[$data["pk"]]);
        unset($data["pk"]);
        unset($data["table"]);

        foreach ($ignore as $value) {
            if(isset($data[$value])) unset($data[$value]);
        }

        $pk = $originDate[$originDate["pk"]];

        // Edit
        if($originDate[$originDate["pk"]]){
            self::update($originDate["table"], $data, "{$originDate['pk']} = {$originDate[$originDate['pk']]}");
        }else{ // Insert
            $pk = self::insert($originDate["table"], $data);
        }

        return $pk;
    }

    public static function delete($table, $conditions){
        GLOBAL $connectDB;
        
        $query = "DELETE FROM $table WHERE $conditions";
        $stmt = $connectDB->prepare($query);

        return $stmt->execute();
    }
}
