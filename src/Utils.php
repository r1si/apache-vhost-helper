<?php


namespace Apache\VhostHelper;


class Utils
{
    /**
     * Normalize comment text
     * @param $comment_text
     * @return string|string[]
     */
    public static function normalizeComment($comment_text)
    {
        // remove existing #
        return str_replace("#", " ", $comment_text);
    }
}