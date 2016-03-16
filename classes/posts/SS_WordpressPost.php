<?php
/**
 * Class SS_WordpressPost
 */
class SS_WordpressPost extends SS_Post
{
    /**
     * @param $post
     * @return array
     */
    public function transform($post)
    {
        return [
            'id'        => $post->ID,
            'content'   => apply_filters('the_content', $post->post_content),
            'excerpt'   => apply_filters('the_excerpt', $post->post_excerpt),
            'timestamp' => get_the_time('U', $post->ID),
            'permalink' => get_permalink($post->ID)
        ];
    }
}
