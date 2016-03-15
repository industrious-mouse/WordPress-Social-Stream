<?php
/**
 * Class SS_FacebookPost
 */
class SS_FacebookPost extends SS_Post
{
    /**
     * @param $post
     * @return array
     * @todo Update with a proper transformer — No data to test with
     */
    public function transform($post)
    {
        return (array) $post;
    }

    /**
     * Parse the Facebook text with Regex to load links
     * @param  string $content The original post content
     * @return string          The parsed post content
     */
    public function parse($content)
    {
        $content = nl2br($content);
        $content = preg_replace('/(http:\/\/[a-z0-9\.\/]+)/i', '<a href="$1" target="_blank">$1</a>', $content);
        $content = preg_replace('/(https:\/\/[a-z0-9\.\/]+)/i', '<a href="$1" target="_blank">$1</a>', $content);

        return $content;
    }
}