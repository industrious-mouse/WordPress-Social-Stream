<?php
/**
 * Class SS_InstagramPost
 */
class SS_InstagramPost extends SS_Post
{
    /**
     * @param $post
     * @return array
     */
    public function transform($post)
    {
        return [
            'id'        => $post['id'],
            'content'   => $post['caption']['text'],
            'image'     => $post['images']['standard_resolution']['url'],
            'timestamp' => $post['created_time'],
            'permalink' => $post['link']
        ];
    }

    /**
     * Parse the Instagram text with Regex to load links, @replies and hashtags
     * @param  string $content The original post content
     * @return string          The parsed post content
     */
    public function parse($content)
    {
        $content = nl2br($content);
        $content = preg_replace('/(http:\/\/[a-z0-9\.\/]+)/i', '<a href="$1" target="_blank">$1</a>', $content);
        $content = preg_replace('/(https:\/\/[a-z0-9\.\/]+)/i', '<a href="$1" target="_blank">$1</a>', $content);
        $content = preg_replace('/( @|^@)(\w+)/', '<a rel="nofollow" href="http://www.instagram.com/$2" target="_blank" title="Follow $2 on Instagram">$1$2</a>', $content);
        $content = preg_replace('/( #|^#)(\w+)/', '<a rel="nofollow" href="https://instagram.com/explore/tags/$2" target="_blank" title="$2">$1$2</a>', $content);

        return $content;
    }
}