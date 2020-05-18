<?php


namespace Apache\VhostHelper;

class ApacheConf
{
    private $vhosts = [];
    private $initial_comment;
    private $final_comment;

    public function __construct($initial_comment = "", $final_comment = "")
    {
        $this->initial_comment = $initial_comment;
        $this->final_comment = $final_comment;
    }

    public function addVirtualHost(VirtualHost $virtualHost)
    {
        $this->vhosts[] = $virtualHost;
        return $this;
    }

    /**
     * Export apache conf to plain text
     *
     * @return String
     */
    public function toString()
    {
        // start with initial comment
        if (!empty($this->initial_comment)) {
            $content = "# " . Utils::normalizeComment($this->initial_comment) . PHP_EOL . PHP_EOL;
        }
        else {
            $content = "";
        }

        // then print all vhosts
        foreach ($this->vhosts as $vhost) {
            $content .= $vhost->toString() . PHP_EOL . PHP_EOL;
        }

        // finish with last comment
        if (!empty($this->final_comment)) {
            $content .= "# " . Utils::normalizeComment($this->final_comment);
        }

        return $content;
    }

    /**
     * Saves vh in to a file
     * @param string $filePath
     *
     * @return int|bool
     */
    public function saveToFile($filePath)
    {
        $content = $this->toString();

        return file_put_contents($filePath, $content);
    }

}