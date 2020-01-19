<?php


class Constantes
{
    // TODO voir les liens
    // EN local
    // const PATH_IMAGES = '/inside/includes/images';
    // En hebergeur
    const PATH_IMAGES = '/includes/images';
    const PATH_AVATARS = Constantes::PATH_IMAGES . '/profil/avatars';
    const PATH_COLLECTORS_IMAGES = Constantes::PATH_IMAGES . '/collector';
//    const PATH_EMOTICONES = '/inside/includes/icons/common/smileys';
    const PATH_EMOTICONES = '/includes/icons/common/smileys';
    const SECRET_KEY = 'YOUR_SECRET_KEY';

    static function showConstant()
    {
        echo self::PATH_IMAGES . "\n";
        echo self::PATH_AVATARS . "\n";
    }

    /**
     * Configure Slim app
     */
    const CONFIG = ['settings' => [
        'addContentLengthHeader' => false,
        'displayErrorDetails' => true,
    ]];
}