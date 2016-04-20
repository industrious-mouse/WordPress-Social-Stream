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
            'id'        => get_the_ID(),
            'content'   => get_the_content(),
            'excerpt'   => get_the_excerpt(),
            'timestamp' => get_the_time('U'),
            'permalink' => get_permalink()
        ];

        wp_reset_postdata();

        return $return;
    }
}
