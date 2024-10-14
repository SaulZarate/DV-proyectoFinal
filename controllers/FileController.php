<?

class FileController
{

    const validExtensionsImage = ["jpg", "jpeg", "png", "gif"];
    const validExtensionsVideo = ["mp4"];

    private $file = null;
    private $fileTypeValidate = "";
    private $maxFileSize = 0;
    private $destinationFolder = "";


    /**
     * Constructor
     * 
     * @param array $file $_FILES[file] of form
     * @param array|null $options array("maxFileSize" => <int>, "destination" => "usuarios/perfil", "typeValidate" => "image|video")
     * 
     * @return void
     */
    public function __construct($file, $destino = "", $options = [])
    {
        $this->file = $file;
        $this->destinationFolder = "uploads/" . $destino;

        // Opcionales
        $this->maxFileSize = isset($options["maxFileSize"]) && $options["maxFileSize"] ? $options["maxFileSize"] : self::convertToBytes(ini_get('upload_max_filesize'));
        if (isset($options["typeValidate"]) && $options["typeValidate"]) $this->fileTypeValidate = $options["typeValidate"];
    }

    /**
     * Valida la extension del archivo, su tamaño y lo guarda en el servidor
     * 
     * @return array array(status => "OK|ERROR_{type}", ...)
     */
    public function save()
    {
        $fileExtension = strtolower(pathinfo($this->file["name"], PATHINFO_EXTENSION));
        $newName = uniqid() . "-" . date("Y_m_d-H_i_s") . ".{$fileExtension}";

        // Valido la extensión
        $resultValidate = $this->validateExtension($fileExtension, $this->getExtensionesValidas());
        if ($resultValidate["status"] != "OK") return $resultValidate;

        // Valido el tamaño
        $resultValidate = $this->validateSize();
        if ($resultValidate["status"] != "OK") return $resultValidate;

        // Guardo el archivo
        return $this->upload($newName);
    }


    private function getExtensionesValidas()
    {
        $extensiones = array();

        switch ($this->fileTypeValidate) {
            case 'image':
                $extensiones = self::validExtensionsImage;
                break;

            case 'video':
                $extensiones = self::validExtensionsVideo;
                break;

            default:
                $extensiones = array_merge(self::validExtensionsImage, self::validExtensionsVideo);
                break;
        }

        return $extensiones;
    }

    private function validateExtension($extension, $extensionesValidas)
    {
        $result = array("status" => "OK");

        if (!in_array($extension, $extensionesValidas)) {
            $result = array(
                "status" => "ERROR_EXTENSION",
                "error" => array(
                    "message" => "El archivo no es válido. Extensiones válidas: " . implode(", ", $extensionesValidas),
                    "extensionesValidas" => $extensionesValidas
                )
            );
        }

        return $result;
    }

    private function validateSize()
    {
        $result = array("status" => "OK");

        if ($this->file["size"] > $this->maxFileSize) {
            $result = array(
                "status" => "ERROR_SIZE",
                "error" => array(
                    "message" => "El archivo es muy grande para ser guardado. El máximo permitido es de " . ini_get('upload_max_filesize'),
                    "maxSize" => $this->maxFileSize
                )
            );
        }
        return $result;
    }

    private function upload($fileName)
    {

        $destinationFolder = PATH_SERVER . $this->destinationFolder;
        $pathDestinationFile = $destinationFolder . "/" . $fileName;

        // Si no existe la carpeta la creo
        if (!file_exists($destinationFolder)) mkdir($destinationFolder, 0777, true);

        // Guardo el archivo
        if (self::saveFile($this->file, $pathDestinationFile)) {
            $result = array(
                "status" => "OK",
                "fileType" => $this->fileTypeValidate,
                "path" => $this->destinationFolder . "/{$fileName}",
                "fullPath" => $pathDestinationFile,
                "url" => DOMAIN_NAME . $this->destinationFolder . "/{$fileName}",
            );
        } else {
            $result = array(
                "status" => "ERROR_UPLOAD",
                "error" => [
                    "message" => "No se pudo guardar el archivo, por favor vuelva a intentarlo o contacte a soporte",
                    "data" => array(
                        "code" => $this->file["error"],
                        "descriptionsErrors" => "
                            UPLOAD_ERR_INI_SIZE = Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini. \n\n
                            UPLOAD_ERR_FORM_SIZE = Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form. \n\n
                            UPLOAD_ERR_PARTIAL = Value: 3; The uploaded file was only partially uploaded. \n\n
                            UPLOAD_ERR_NO_FILE = Value: 4; No file was uploaded. \n\n
                            UPLOAD_ERR_NO_TMP_DIR = Value: 6; Missing a temporary folder. Introduced in PHP 5.0.3. \n\n
                            UPLOAD_ERR_CANT_WRITE = Value: 7; Failed to write file to disk. Introduced in PHP 5.1.0. \n\n
                            UPLOAD_ERR_EXTENSION = Value: 8;
                        "
                    )
                ]
            );
        }

        return $result;
    }


    /**
     * Valida si la extension de la imagen es valida
     * 
     * @param array $file $_FILES[file] of form
     * 
     * @return bool
     */
    public static function isValidImageExtension($file, $extensiones = [])
    {

        $fileExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $extensionesValidas = $extensiones ? $extensiones : self::validExtensionsImage;

        return in_array($fileExtension, $extensionesValidas);
    }

    /**
     * Valida si la extension del video es valida
     * 
     * @param array $file $_FILES[file] of form
     * 
     * @return bool
     */
    public static function isValidVideoExtension($file, $extensiones = [])
    {

        $fileExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $extensionesValidas = $extensiones ? $extensiones : self::validExtensionsVideo;

        return in_array($fileExtension, $extensionesValidas);
    }

    /**
     * Guarda un archivo en el servidor
     * 
     * @param array $file $_FILES[file] of form
     * @param string $destination Destino del archivo .../img/archivo.jpg
     * 
     * @return bool
     */
    public static function saveFile($file, $destination)
    {
        return move_uploaded_file($file["tmp_name"], $destination);
    }

    /**
     * Elimina toda la carpeta
     * @return void
     */
    public static function deleteFolder($dir)
    {
        if (!is_dir($dir)) return;

        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            if(is_dir("{$dir}/{$file}") && !is_link("$dir/$file")){
                self::deleteFolder("{$dir}/{$file}");
            }else{
                unlink("{$dir}/{$file}");
            }
        }
        rmdir($dir);
    }

    /**
     * Convierte el tamaño de un archivo a bytes
     * 
     * @param string $size $_FILES["name"]["size"]| Examples: 20M, 1G, 800K
     * @return int
     */
    public static function convertToBytes($size)
    {
        $unit = strtoupper(substr($size, -1)); // Obtiene la última letra (K, M, G, etc.)
        $size = (int)$size; // Obtiene el valor numérico

        switch ($unit) {
            case 'G':
                $size *= 1024 * 1024 * 1024; // Si es en Gigabytes
                break;
            case 'M':
                $size *= 1024 * 1024; // Si es en Megabytes
                break;
            case 'K':
                $size *= 1024; // Si es en Kilobytes
                break;
        }

        return $size;
    }
}
