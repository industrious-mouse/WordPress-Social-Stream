<?php
/**
 * Class SS_Post
 */
abstract class SS_Post
{
    /**
     * @var
     */
    public $post;

    /**
     * SS_PostType constructor.
     *
     * @param $post
     */
    public function __construct($post)
    {
        $this->post = $this->transform($post);
    }

    /**
     * @return string
     */
    public function return_class()
    {
        return strtolower(str_replace(['SS_', 'Post'], '', get_called_class()));
    }

    /**
     * Check if it's a Wordpress post
     * @return boolean
     */
    function is_wordpress_post()
    {
        return $this instanceof SS_WordpressPost;
    }

    /**
     * Check if it's a Twitter post
     * @return boolean
     */
    function is_twitter_post()
    {
        return $this instanceof SS_TwitterPost;
    }

    /**
     * Check if it's a Facebook post
     * @return boolean
     */
    function is_facebook_post()
    {
        return $this instanceof SS_FacebookPost;
    }

    /**
     * Check if it's a Instagram post
     * @return boolean
     */
    function is_instagram_post()
    {
        return $this instanceof SS_InstagramPost;
    }

    /**
     * @param $post
     * @return mixed
     */
    abstract public function transform($post);
}