<?

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

class Database{

    public static function getOne($query){
        GLOBAL $connectDB;
        
        $stmt = $connectDB->prepare($query);
        $stmt->execute();

        return $stmt->fetchObject(PDO::FETCH_OBJ);
    }

    public static function getAll($query){
        GLOBAL $connectDB;
        
        $stmt = $connectDB->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function select($table, $columns = "*", $conditions = ""){
        GLOBAL $connectDB;
        
        $query = "SELECT $columns FROM $table";
        if (!empty($conditions)) $query .= " WHERE $conditions";

        $stmt = $connectDB->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

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

    public static function update($table, $data, $conditions){
        GLOBAL $connectDB;
        
        $setClause = "";
        foreach ($data as $key => $value) {
            $setClause .= "$key = :$key, ";
        }
        $setClause = rtrim($setClause, ", ");

        $query = "UPDATE $table SET $setClause WHERE $conditions";
        $stmt = $connectDB->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public static function delete($table, $conditions){
        GLOBAL $connectDB;
        
        $query = "DELETE FROM $table WHERE $conditions";
        $stmt = $connectDB->prepare($query);

        return $stmt->execute();
    }
}
