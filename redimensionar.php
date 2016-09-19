<?php

class RedimensionarFor64 {
		
	private $image;
	private $file;
	private $width;
	private $path;
	private $result;
	private $error;

	/**
	|
	| ex: $u = new RedimensionarFor64($_FILES['imagem'], 100, 'imagens/');
	| if ($u->getResult()) {
	| 	echo 'funcionou';
	| } else {
	|	echo 'erro';
	| }
	|
	*/
	public function __construct( array $file, $width = null, $path = null ) {
		$this->file = $file;
		$this->width = ( ( int ) $width ? $width : 1024 );
		$this->path = ( ( string ) $path ? $path : 'imgs/' );
		$this->name = $file['name'];
		if ($this->validarTipos()) {
			$this->criarImagemComBaseNaOriginal();
			$this->redimensionar();
		}
	}

	public function getResult() {
		return $this->result;
	}

	public function getError() {
		return $this->error;
	}

	private function redimensionar() {
		if ( !is_null($this->image) ) {
			$x = imagesx( $this->image );
			$y = imagesy( $this->image );
			$imageX = ( $this->width < $x ? $this->width : $x );
			$imageH = ( $imageX * $y ) / $x;

			$newImage = imagecreatetruecolor( $imageX, $imageH );
			imagealphablending( $newImage, false );
			imagesavealpha( $newImage, true );
			imagecopyresampled( $newImage, $this->image, 0, 0, 0, 0, $imageX, $imageH, $x, $y );

			switch ( $this->file['type'] ) :
	            case 'image/jpg':
	            case 'image/jpeg':
	            case 'image/pjpeg':
	                imagejpeg( $newImage, $this->path . $this->name );
	                break;
	            case 'image/png':
	            case 'image/x-png':
	                imagepng( $newImage, $this->path . $this->name );
	                break;
	        endswitch;

	        $this->result = file_get_contents($this->path . $this->name);

	        imagedestroy($this->image);
	        imagedestroy($newImage);
	    } else {
	    	$this->result = "Arquivo inválido!";
			$this->error = true;
	    }
	}

	private function validarTipos() {
		switch ( $this->file['type'] ) {
			case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
				return true;
			case 'image/png':
            case 'image/x-png':
            	return true;
            case 'application/octet-stream':
            	return true;
            default:
            	$this->result = "O tipo da imagem é inválido!";
				$this->error = true;
				return false;
		}		
	}

	private function criarImagemComBaseNaOriginal() {
		switch ( $this->file['type'] ) {
			case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
				$this->image = imagecreatefromjpeg( $this->file['tmp_name'] );
				break;
			case 'image/png':
            case 'image/x-png':
            	$this->image = imagecreatefrompng( $this->file['tmp_name'] );
		}
	}

}