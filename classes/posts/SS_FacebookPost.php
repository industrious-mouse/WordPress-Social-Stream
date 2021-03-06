<?php
/**
 * Class SS_FacebookPost
 */
class SS_FacebookPost extends SS_Post
{
    /**
     * @param $post
     * @return array
     */
    public function transform($post)
    {
        $image = null;
        $content = isset($post['message']) ? $post['message'] : $post['story'];

        if(isset($post['attachments']['data'][0]['media']))
        {
            $image = $post['attachments']['data'][0]['media']['image']['src'];

            if(!$content)
            {
                $content = $post['attachments']['data'][0]['description'];
            }
        }

        return [
            'id'        => $post['id'],
            'content'   => $content,
            'image'     => $image,
            'timestamp' => strtotime($post['created_time']),
            'permalink' => self::return_permalink($post['id'])
        ];
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

    /**
     * @param $id
     * @return string
     */
    private static function return_permalink($id)
    {
        return 'https://facebook.com' . str_replace('_', '/posts/', $id);
    }
}
