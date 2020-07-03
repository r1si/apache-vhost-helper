<?php

namespace Apache\VhostHelper;

class VirtualHost
{
    private $address;
    private $port;
    private $classes_content = [];
    private $comment;
    const INDENTATION_SPACES = "    ";
    const INDENTATION_SPACES_DOUBLE = "        ";

    public function __construct($address, $port, $start_comment = "")
    {
        $this->address = $address;
        $this->port = $port;
        $this->comment = $start_comment;
    }

    public function addDirective($name, $value)
    {
        $this->classes_content[] = new Directive($name, $value);
        return $this;
    }

    public function addBreak()
    {
        $this->classes_content[] = new BreakLine();
        return $this;
    }

    public function addDirectory(Directory $directory)
    {
        $this->classes_content[] = $directory;
        return $this;
    }

    public function enableSSL($path_crt, $path_key, $path_ca)
    {

        $ssl_directive = new AnObj(array(
            "toString" => function () use ($path_crt, $path_key, $path_ca) {
                $content = self::INDENTATION_SPACES . "SSLEngine on" . PHP_EOL;
                $content .= self::INDENTATION_SPACES_DOUBLE . "SSLCertificateFile " . $path_crt . PHP_EOL;
                $content .= self::INDENTATION_SPACES_DOUBLE . "SSLCertificateKeyFile " . $path_key . PHP_EOL;
                $content .= self::INDENTATION_SPACES_DOUBLE . "SSLCACertificateFile " . $path_ca . PHP_EOL;
                return $content;
            }
        ));

        $this->classes_content[] = $ssl_directive;
        return $this;
    }

    public function enablePHP($php_socket_url)
    {
        $php_directive = new AnObj(array(
            "toString" => function () use ($php_socket_url) {
                $content = self::INDENTATION_SPACES . '<FilesMatch ".+\.ph(ar|p|tml)$">' . PHP_EOL;
                $content .= self::INDENTATION_SPACES_DOUBLE . 'SetHandler "' . $php_socket_url . '"' . PHP_EOL;
                $content .= self::INDENTATION_SPACES . '</FilesMatch>' . PHP_EOL;
                return $content;
            }
        ));

        $this->classes_content[] = $php_directive;
        return $this;
    }


    public function redirect($type, $from, $to)
    {

        $redirect_directive = new AnObj(array(
            "toString" => function () use ($type, $from, $to) {
                return self::INDENTATION_SPACES . "Redirect " . $type . " " . $from . " " . $to . PHP_EOL;
            }
        ));

        $this->classes_content[] = $redirect_directive;
        return $this;
    }

    /**
     * Converts vh to plain text
     *
     * @return
     */
    public function toString()
    {
        if (!empty($this->comment)) {
            $content = "# " . Utils::normalizeComment($this->comment) . PHP_EOL;
        }
        else {
            $content = "";
        }

        $content .= "<VirtualHost {$this->address}:{$this->port}>" . PHP_EOL;

        foreach ($this->classes_content as $single_class) {
            $content .= $single_class->toString();
        }

        $content .= "</VirtualHost>";

        return $content;
    }

    /**
     * Saves vh in to a file
     * @param string $filePath
     *
     * @return
     */
    public function saveToFile($filePath)
    {
        $content = $this->toString();

        file_put_contents($filePath, $content);
    }
}