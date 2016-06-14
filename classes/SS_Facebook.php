<?php

/**
 * Class to fetch posts from Facebook
 */
class SS_Facebook extends SS_Provider
{
    /**
     * Name of the stored WP Transient
     */
    protected $transient_name = 'facebook_posts';

    /**
     * Access token from Facebook
     * @var string
     */
    private $token;

    /**
     * Fetch Facebook posts
     * @return self
     */
    public function fetch() {
        if(!$this->valid()) {
            $this->posts = array();
            return $this;
        }

        if(!$this->check_cache()) {
            $this->get_token()->get_posts();
        }

        return $this;
    }

    /**
     * Check all fields are set in the settings class
     * @return bool
     */
    private function valid() {
        return ($this->settings->facebook && $this->settings->client_id && $this->settings->client_secret);
    }

    /**
     * Get the token from Facebook
     * @return self
     */
    private function get_token() {
        $this->token = file_get_contents('https://graph.facebook.com/oauth/access_token?client_id='.$this->settings->client_id.'&client_secret='.$this->settings->client_secret.'&grant_type=client_credentials&limit=1');
        return $this;
    }

    /**
     * Get the posts from Facebook
     * @return self
     */
    private function get_posts() {

        $query = '?' . $this->token . '&' . http_build_query([
            'fields' => 'attachments,message,created_time,story,id,description'
        ]);

        $url = 'https://graph.facebook.com/'.$this->settings->facebook.'/posts' . $query;

        $feed = json_decode(file_get_contents($url), true);

        $this->posts = array_map(function($item) {
            return new SS_FacebookPost($item);
        }, $feed['data']);

        $this->write_cache();
        return $this;
    }
}