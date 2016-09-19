<?php

/**
 * Upload.class.php [ TIPO ]
 * Responsável por executar uploads de imagens, arquivos e mídias no sistema.
 * 
 * @copyright (c) 2015, Gideao M. Silva GALLASKO TECNOLOGIA
 */

//namespace MainFramework\Helpers;

class Upload {

    private $file;
    private $name;
    private $send;

    /** IMAGE UPLOAD */
    private $width;
    private $image;

    /** RESULTSET */
    private $result;
    private $error;

    /** DIRETÓRIOS */
    private $folder;
    private static $baseDir;

    /**
     * <b>Construtor:</b> Verifica e caso seja necessário cria a pasta uploads na raiz no projeto.
     * 
     * @param STRING $baseDir = Nome da pasta a ser criada caso não queira que se chame uploads que é o padrão.
     */
    public function __construct($baseDir = null) {
        self::$baseDir = ((string) $baseDir ? $baseDir . '/' : '../uploads/');
        if (!file_exists(self::$baseDir) && !is_dir(self::$baseDir)) :
            mkdir(self::$baseDir, 0777);
        endif;
    }

    /**
     * <b>image:</b> Responsável por enviar imagens para o servidor. 
     * É possível passar uma largura personalizada, caso não passe o padrão será 1024.
     * 
     * @param FILES $image = Enviar envelope de $_FILES (JPG ou PNG). ex: $_FILES['imagem']
     * @param STRING $name = Nome da imagem ( ou do artigo ) que será dado à imagem
     * @param INT $width = Largura da imagem ( 1024 padrão )
     * @param STRING $folder = Pasta personalizada
     */
    public function image(array $image, $name = null, $width = null, $folder = null) {
        $this->file = $image;
        $this->name = ( (string) $name ? $name : substr($image['name'], 0, strrpos($image['name'], '.')) );
        $this->width = ( (int) $width ? $width : 1024 );
        $this->folder = ( (string) $folder ? $folder : 'images' );

        $this->checkFolder($this->folder);
        $this->setFileName();
        $this->uploadImage();
    }

    /**
     * <b>file:</b> Responsável por enviar arquivos para o servidor. 
     * É possível passar um tamanho personalizado, caso não passe o padrão será 2mb.
     * 
     * @param FILES $file = Enviar envelope de $_FILES (PDF ou DOCX). ex: $_FILES['arquivo']
     * @param STRING $name = Nome do arquivo ( ou do artigo ) que será dado ao arquivo
     * @param STRING $folder = Pasta personalizada
     * @param INT $maxFileSize = Tamanho máximo do arquivo (padrão 2mb)
     */
    public function file(array $file, $name = null, $folder = null, $maxFileSize = null) {
        $this->file = $file;
        $this->name = ( (string) $name ? $name : substr($file['name'], 0, strrpos($file['name'], '.')) );
        $this->folder = ( (string) $folder ? $folder : 'files' );
        $maxFileSize = ( (int) $maxFileSize ? $maxFileSize : 2 );

        $fileAccept = [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/pdf'
        ];

        if ($this->file['size'] > ($maxFileSize * (1024 * 1024))) :
            $this->result = false;
            $this->error = "Arquivo muito grande, tamanho máximo permitido de {$maxFileSize}mb";
        elseif (!in_array($this->file['type'], $fileAccept)) :
            $this->result = false;
            $this->error = "Tipo de arquivo não aceito, envie .PDF ou .DOCX!";
        else :
            $this->checkFolder($this->folder);
            $this->setFileName();
            $this->moveFile();
        endif;
    }
    
    /**
     * <b>media:</b> Responsável por enviar mídia para o servidor. 
     * É possível passar um tamanho personalizado, caso não passe o padrão será 40mb.
     * 
     * @param FILES $media = Enviar envelope de $_FILES (MP3 ou MP4). ex: $_FILES['midia']
     * @param STRING $name = Nome da mídia ( ou do artigo ) que será dado à mídia
     * @param STRING $folder = Pasta personalizada
     * @param INT $maxFileSize = Tamanho máximo do arquivo (padrão 40mb)
     */
    public function media(array $media, $name = null, $folder = null, $maxFileSize = null) {
        $this->file = $media;
        $this->name = ( (string) $name ? $name : substr($media['name'], 0, strrpos($media['name'], '.')) );
        $this->folder = ( (string) $folder ? $folder : 'media' );
        $maxFileSize = ( (int) $maxFileSize ? $maxFileSize : 40 );

        $fileAccept = [
            'audio/mp3',
            'video/mp4'
        ];

        if ($this->file['size'] > ($maxFileSize * (1024 * 1024))) :
            $this->result = false;
            $this->error = "Arquivo muito grande, tamanho máximo permitido de {$maxFileSize}mb";
        elseif (!in_array($this->file['type'], $fileAccept)) :
            $this->result = false;
            $this->error = "Tipo de arquivo não aceito, envie audio MP3 ou vídeo MP4!";
        else :
            $this->checkFolder($this->folder);
            $this->setFileName();
            $this->moveFile();
        endif;
    }

    /**
     * <b>getResult:</b> Responsável por verificar se o Upload foi executado ou não. Retorna
     * uma string com o caminho e nome do arquivo ou FALSE.
     * 
     * @return STRING  = Caminho e Nome do arquivo ou False
     */
    function getResult() {
        return $this->result;
    }

    /**
     * <b>getError:</b> Retorna um array associativo com um code, um title, um erro e um tipo.
     * 
     * @return ARRAY $Error = Array associatico com o erro
     */
    function getError() {
        return $this->error;
    }

    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */

    /* Verifica e cria os diretórios com base no tipo de arquivo, ano e mês! */
    private function checkFolder($folder) {
        list($y, $m) = explode('/', date('Y/m'));
        $this->createFolder("{$folder}");
        $this->createFolder("{$folder}/{$y}");
        $this->createFolder("{$folder}/{$y}/{$m}/");
        $this->send = "{$folder}/{$y}/{$m}/";
    }

    /* Verifica e cria o diretório base! */
    private function createFolder($folder) {
        if (!file_exists(self::$baseDir . $folder) && !is_dir(self::$baseDir . $folder)) :
            mkdir(self::$baseDir . $folder, 0777);
        endif;
    }

    /* Verifica e monta o nome dos arquivos tratando a string! */
    private function setFileName() {
        $fileName = $this->name;
        if (file_exists(self::$baseDir . $this->send . $fileName)) :
            $fileName = $this->name . '-' . time() . strrchr($this->file['name'], '.');
        endif;
        $this->name = $fileName;
    }

    /* Realiza o upload de imagens redimensionando a mesma */
    private function uploadImage() {
        switch ($this->file['type']) :
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                $this->image = imagecreatefromjpeg($this->file['tmp_name']);
                break;
            case 'image/png':
            case 'image/x-png':
                $this->image = imagecreatefrompng($this->file['tmp_name']);
                break;
        endswitch;

        if (!$this->image) :
            $this->result = false;
            $this->error = "Tipo de arquivo inválido, envie imagens JPG ou PNG";
        else :
            $x = imagesx($this->image);
            $y = imagesy($this->image);
            $imageX = ( $this->width < $x ? $this->width : $x );
            $imageH = ( $imageX * $y ) / $x;

            $newImage = imagecreatetruecolor($imageX, $imageH);
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $imageX, $imageH, $x, $y);

            switch ($this->file['type']) :
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/pjpeg':
                    imagejpeg($newImage, self::$baseDir . $this->send . $this->name);
                    break;
                case 'image/png':
                case 'image/x-png':
                    imagepng($newImage, self::$baseDir . $this->send . $this->name);
                    break;
            endswitch;

            if (!$newImage) :
                $this->result = false;
                $this->error = "Tipo de arquivo inválido, envie imagens JPG ou PNG";
            else :
                //$this->result = true;
                $this->result = $this->send . $this->name;
                $this->error = null;
            endif;

            imagedestroy($this->image);
            imagedestroy($newImage);
        endif;
    }

    /* Envia mídias e arquivos */
    private function moveFile() {
        if (move_uploaded_file($this->file['tmp_name'], self::$baseDir . $this->send . $this->name)) :
            $this->result = $this->send . $this->name;
            $this->error = null;
        else :
            $this->result = false;
            $this->error = 'Erro ao mover arquivo. Favor tente mais tarde!';
        endif;
    }

}