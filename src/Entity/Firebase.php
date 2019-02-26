<?php

namespace App\Entity;


class Firebase
{

    private static $instance;

    private function Firebase() { }
    private function clone() { }

    public static function getInstance($DEFAULT_URL, $DEFAULT_TOKEN)
    {
        if (!isset(self::$instance))
        {
            self::$instance = new \Firebase\FirebaseLib($DEFAULT_URL, $DEFAULT_TOKEN);
        }

        return self::$instance;
    }

    /**
     * @return string
     */
    public function getDEFAULTURL(): string
    {
        return $this->DEFAULT_URL;
    }

    /**
     * @return string
     */
    public function getDEFAULTTOKEN(): string
    {
        return $this->DEFAULT_TOKEN;
    }

    /**
     * @return string
     */
    public function getDEFAULTPATH(): string
    {
        return $this->DEFAULT_PATH;
    }



}

