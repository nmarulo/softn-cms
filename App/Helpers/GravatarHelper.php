<?php
/**
 * softn-cms
 */

namespace App\Helpers;

use App\Rest\Responses\Settings\GrAvatarSettingsFormResponse;

/**
 * Class Gravatar
 * @author Nicolás Marulanda P.
 */
class GravatarHelper {
    
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
    
    const DEFAULT_SIZE_32     = 32;
    
    const DEFAULT_SIZE_64     = 64;
    
    const DEFAULT_SIZE_128    = 128;
    
    const DEFAULT_SIZE_256    = 256;
    
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
    
    /** @var bool Usar con "f=y" o "forcedefault=y" */
    private $forceDefault;
    
    /** @var string Usar con "r=" o "rating=" y uno de los valores "RATING_*" */
    private $rating;
    
    /** @var string */
    private $email;
    
    /**
     * GravatarHelper constructor.
     *
     * @param GrAvatarSettingsFormResponse $response
     * @param string                       $email
     */
    public function __construct(GrAvatarSettingsFormResponse $response, string $email = '') {
        $this->forceDefault = boolval($response->gravatarForceDefault->settingValue);
        $this->email        = $email;
        $this->setSize(intval($response->gravatarSize->settingValue));
        $this->setDefaultImage($response->gravatarImage->settingValue);
        $this->setRating($response->gravatarRating->settingValue);
    }
    
    /**
     * @return int
     */
    public function getSize(): int {
        return $this->size;
    }
    
    /**
     * @param int  $size
     * @param bool $addParam
     */
    public function setSize(int $size, $addParam = TRUE): void {
        $size       = $addParam ? "s=$size" : $size;
        $this->size = $size;
    }
    
    /**
     * @return string
     */
    public function getDefaultImage(): string {
        return $this->defaultImage;
    }
    
    /**
     * @param string $defaultImage
     * @param bool   $addParam
     */
    public function setDefaultImage(string $defaultImage, bool $addParam = TRUE): void {
        $defaultImage       = $addParam ? "d=$defaultImage" : $defaultImage;
        $this->defaultImage = $defaultImage;
    }
    
    /**
     * @return bool
     */
    public function getForceDefault(): bool {
        return $this->forceDefault;
    }
    
    /**
     * @param bool $forceDefault
     */
    public function setForceDefault(bool $forceDefault): void {
        $this->forceDefault = $forceDefault;
    }
    
    /**
     * @return string
     */
    public function getRating(): string {
        return $this->rating;
    }
    
    /**
     * @param string $rating
     * @param bool   $addParam
     */
    public function setRating($rating, $addParam = TRUE): void {
        $rating       = $addParam ? "r=$rating" : $rating;
        $this->rating = $rating;
    }
    
    /**
     * NOTA: previamente se debe estar validado el email.
     *
     * @param string $email
     */
    public function setEmail($email): void {
        $this->email = $email;
    }
    
    public function get(): string {
        $email        = md5($this->email);
        $forceDefault = '';
        
        if ($this->forceDefault) {
            $forceDefault = self::FORCE_DEFAULT;
        }
        
        return sprintf('%1$s%2$s?%3$s&%4$s&%5$s&%6$s', self::URL, $email, $this->size, $this->defaultImage, $this->rating, $forceDefault);
    }
}
