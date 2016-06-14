<?php
/**
 * Class SS_TwitterPost
 */
class SS_TwitterPost extends SS_Post
{
    /**
     * @param $post
     * @return array
     */
    public function transform($post)
    {
        $image = null;

        if(isset($post['entities']['media'][0]))
        {
            $image = $post['entities']['media'][0]['media_url_https'] . ':medium';
        }

        return [
            'id'        => $post['id'],
            'content'   => $post['text'],
            'image'     => $image,
            'timestamp' => strtotime($post['created_at']),
            'permalink' => self::return_permalink($post['id_str'])
        ];
    }

    /**
     * Parse the Twitter text with Regex to load links, @replies and hashtags
     * @param  string $content The original post content
     * @return string          The parsed post content
     */
    public function parse($content)
    {
        $content = nl2br($content);
        $content = preg_replace('/(http:\/\/[a-z0-9\.\/]+)/i', '<a href="$1" target="_blank">$1</a>', $content);
        $content = preg_replace('/(https:\/\/[a-z0-9\.\/]+)/i', '<a href="$1" target="_blank">$1</a>', $content);
        $content = preg_replace('/( @|^@)(\w+)/', '<a rel="nofollow" href="http://www.twitter.com/$2" target="_blank" title="Follow $2 on Twitter">$1$2</a>', $content);
        $content = preg_replace('/( #|^#)(\w+)/', '<a rel="nofollow" href="https://twitter.com/#!/search?q=%23$2" target="_blank" title="$2">$1$2</a>', $content);

        return $content;
    }

    /**
     * @param $id
     * @return string
     */
    private static function return_permalink($id)
    {
        return 'https://twitter.com/statuses/' . $id;
    }
}