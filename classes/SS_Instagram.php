<?php

/**
 * Class to fetch posts from Instagram
 */
class SS_Instagram extends SS_Provider
{
    /**
     * Name of the stored WP Transient
     */
    protected $transient_name = 'instagram_posts';

    /**
     * Fetch posts from WordPress
     * @return self
     */
    public function fetch($number = 25)
    {
        if(!$this->valid())
        {
            $this->posts = array();
            return $this;
        }

        if(!$this->check_cache())
        {
            $content = file_get_contents('https://www.instagram.com/' . $this->settings->instagram_username . '/media/');
            $feed = json_decode($content, true);

            if(!isset($feed['status']) || $feed['status'] !== 'ok')
            {
                $this->posts = [];
            }
            else
            {
                $this->posts = array_map(function($item) {
                    return new SS_InstagramPost($item);
                }, $feed['items']);

                $this->write_cache();
            }
        }

        return $this;
    }

    /**
     * Checks if the required settings have been set
     * @return self
     */
    private function valid() {
        return ($this->settings->instagram_username);
    }
}
