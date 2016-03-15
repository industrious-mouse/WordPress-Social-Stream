<?php

abstract class SS_Provider
{
    /**
     *
     */
    const CACHE_ENABLED = false;

    /**
     * Posts fetched from Twitter
     * @var array
     */
    public $posts;

    /**
     * Reference to SS_Settings
     * @var object
     */
    protected $settings;

    /**
     * Let the magic begin
     */
    public function __construct()
    {
        $this->settings = new SS_Settings();
    }

    /**
     * Check if the cache date is less than now and if so load the posts from there
     * @return bool
     */
    protected function check_cache()
    {
        if(!self::CACHE_ENABLED)
        {
            return false;
        }

        if($cache = get_transient($this->transient_name))
        {
            $this->posts = $cache['posts'];
            return true;
        }

        return false;
    }

    /**
     * Write to the cache
     * @return void
     */
    protected function write_cache()
    {
        $data = array(
            'posts'   => $this->posts,
        );

        set_transient($this->transient_name, $data, strtotime('+1 hours'));
    }
}