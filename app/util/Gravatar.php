<?php
/**
 * Gravatar.php
 */

namespace SoftnCMS\util;

/**
 * Class Gravatar
 * @author Nicolás Marulanda P.
 */
class Gravatar implements \Serializable {
    
    /** do not load any image if none is associated with the email hash, instead return an HTTP 404 (File Not Found) response */
    const DEFAULT_IMAGE_404 = '404';
    
    /** (mystery-man) a simple, cartoon-style silhouetted outline of a person (does not vary by email hash) */
    const DEFAULT_IMAGE_MM = 'mm';
    
    /** a geometric pattern based on an email hash */
    const DEFAULT_IMAGE_IDENTICON = 'identicon';
    
    /** a generated 'monster' with different colors, faces, etc */
    const DEFAULT_IMAGE_MOSTERID = 'monsterid';
    
    /** generated faces with differing features and backgrounds */
    const DEFAULT_IMAGE_WAVATAR = 'wavatar';
    
    /** awesome generated, 8-bit arcade-style pixelated faces */
    const DEFAULT_IMAGE_RETRO = 'retro';
    
    /** a transparent PNG image (border added to HTML below for demonstration purposes) */
    const DEFAULT_IMAGE_BLANK = 'blank';
    
    const URL                 = 'https://www.gravatar.com/avatar/';
    
    /** suitable for display on all websites with any audience type. */
    const RATING_G = 'g';
    
    /** may contain rude gestures, provocatively dressed individuals, the lesser swear words, or mild violence. */
    const RATING_PG = 'pg';
    
    /** may contain such things as harsh profanity, intense violence, nudity, or hard drug use. */
    const RATING_R = 'r';
    
    /** may contain hardcore sexual imagery or extremely disturbing violence. */
    const RATING_X = 'x';
    
    /** If for some reason you wanted to force the default image to always load. */
    const FORCE_DEFAULT = 'f=y';
    
    /** @var int Usar con "s=" o "size=" */
    private $size;
    
    /** @var string Usar con "d=" o "default=" y uno de los valor de "DEFAULT_IMAGE_*" */
    private $defaultImage;
    
    /** @var string Usar con "f=y" o "forcedefault=y" */
    private $forceDefault;
    
    /** @var string Usar con "r=" o "rating=" y uno de los valores "RATING_*" */
    private $rating;
    
    /** @var string */
    private $email;
    
    /**
     * Gravatar constructor.
     */
    public function __construct() {
        $this->forceDefault = FALSE;
        $this->email        = '';
        $this->setSize(64);
        $this->setDefaultImage(self::DEFAULT_IMAGE_MM);
        $this->setRating(self::RATING_G);
    }
    
    public function serialize() {
        return serialize([
            $this->forceDefault,
            $this->email,
            $this->size,
            $this->defaultImage,
            $this->rating,
        ]);
    }
    
    public function unserialize($serialized) {
        list($this->forceDefault, $this->email, $this->size, $this->defaultImage, $this->rating) = unserialize($serialized);
    }
    
    /**
     * @return int
     */
    public function getSize() {
        return $this->size;
    }
    
    /**
     * @param int  $size
     * @param bool $addParam
     */
    public function setSize($size, $addParam = TRUE) {
        $size       = $addParam ? "s=$size" : $size;
        $this->size = $size;
    }
    
    /**
     * @return string
     */
    public function getDefaultImage() {
        return $this->defaultImage;
    }
    
    /**
     * @param string $defaultImage
     * @param bool   $addParam
     */
    public function setDefaultImage($defaultImage, $addParam = TRUE) {
        $defaultImage       = $addParam ? "d=$defaultImage" : $defaultImage;
        $this->defaultImage = $defaultImage;
    }
    
    /**
     * @return string
     */
    public function getForceDefault() {
        return $this->forceDefault;
    }
    
    /**
     * @param bool $forceDefault
     */
    public function setForceDefault($forceDefault) {
        $this->forceDefault = $forceDefault;
    }
    
    /**
     * @return string
     */
    public function getRating() {
        return $this->rating;
    }
    
    /**
     * @param string $rating
     * @param bool   $addParam
     */
    public function setRating($rating, $addParam = TRUE) {
        $rating       = $addParam ? "r=$rating" : $rating;
        $this->rating = $rating;
    }
    
    /**
     * NOTA: previamente se debe estar validado el email.
     *
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function get() {
        $email        = md5($this->email);
        $forceDefault = '';
        
        if ($this->forceDefault) {
            $forceDefault = self::FORCE_DEFAULT;
        }
        
        return sprintf('%1$s%2$s?%3$s&%4$s&%5$s&%6$s', self::URL, $email, $this->size, $this->defaultImage, $this->rating, $forceDefault);
    }
}
