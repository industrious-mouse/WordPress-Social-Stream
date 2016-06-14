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
        setup_postdata($post);

        $return = [
            'id'        => $post->ID,
            'title'     => get_the_title($post),
            'content'   => get_the_content(),
            'excerpt'   => get_the_excerpt(),
            'timestamp' => get_the_time('U', $post),
            'permalink' => get_permalink($post)
        ];

        wp_reset_postdata();

        return $return;
    }
}
