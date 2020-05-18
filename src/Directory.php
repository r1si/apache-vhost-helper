<?php

namespace Apache\VhostHelper;

class Directory
{
    private $path;
    private $directives = [];
    private $classes_content = [];
    const INDENTATION_SPACES = "    ";
    const INDENTATION_SPACES_DOUBLE = "        ";
    const INDENTATION_SPACES_TRIPE = "            ";

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function addDirective($name, $value)
    {
        $this->classes_content[] = new Directive($name, $value);
        return $this;
    }

    public function redirectIf404($type, $domain, $destination)
    {

        $ssl_directive = new AnObj(array(
            "toString" => function () use ($type, $domain, $destination) {
                $content = self::INDENTATION_SPACES . "<IfModule mod_rewrite.c>" . PHP_EOL;
                $content .= self::INDENTATION_SPACES_TRIPE . "RewriteEngine On" . PHP_EOL;
                $content .= self::INDENTATION_SPACES_TRIPE . "RewriteBase /" . PHP_EOL;
                $content .= self::INDENTATION_SPACES_TRIPE . 'RewriteCond %{HTTP_HOST} ^' . $domain . '$' . PHP_EOL;
                $content .= self::INDENTATION_SPACES_TRIPE . "%{REQUEST_FILENAME} !-f" . PHP_EOL;
                $content .= self::INDENTATION_SPACES_TRIPE . '^(.*)$ ' . $destination . '/$1' . PHP_EOL;
                $content .= self::INDENTATION_SPACES_DOUBLE . "</IfModule>" . PHP_EOL;
                return $content;
            }
        ));

        $this->classes_content[] = $ssl_directive;
        return $this;
    }


    public function addBreak()
    {
        $this->classes_content[] = new BreakLine();
        return $this;
    }

    public function toString()
    {
        $content = Directive::INDENTATION_SPACES . "<Directory {$this->path}>" . PHP_EOL;

        foreach ($this->classes_content as $single_class) {
            $content .= Directive::INDENTATION_SPACES . $single_class->toString();
        }

        $content .= Directive::INDENTATION_SPACES . "</Directory>" . PHP_EOL;

        return $content;
    }
}