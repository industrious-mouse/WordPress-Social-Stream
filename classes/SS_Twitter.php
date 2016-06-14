<?php

/**
 * Class to fetch posts from Twitter
 */
class SS_Twitter extends SS_Provider
{
    /**
     * Name of the stored WP Transient
     */
    protected $transient_name = 'twitter_posts';

    /**
     * Reference to TwitterOAuth
     * @var Object
     */
    private $connection;

    /**
     * Let the magic begin
     */
    public function __construct()
    {
        parent::__construct();
        $this->load_oauth();
    }

    /**
     * Load the oauth class
     * @return self
     */
    private function load_oauth() {
        if (!class_exists('TwitterOAuth')) {
            require_once(__DIR__.'/../vendor/twitter_oauth/twitteroauth.php');
        }

        return $this;
    }

    /**
     * Fetch posts from WordPress
     * @return self
     */
    public function fetch($number = 25) {
        if(!$this->valid()) {
            $this->posts = array();
            return $this;
        }

        if(!$this->check_cache())
        {
            $options = array(
                'screen_name' => $this->settings->username,
                'count' => $number,
                'exclude_replies' => true
            );

            $this->connection = new TwitterOAuth($this->settings->consumer_key, $this->settings->consumer_secret, $this->settings->access_token, $this->settings->access_secret);
            $result = $this->connection->get('statuses/user_timeline', $options);

            if(isset($result['errors']))
            {
                $this->posts = [];
            }
            else
            {
                $this->posts = array_map(function($item) {
                    return new SS_TwitterPost($item);
                }, $result);

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
        return ($this->settings->consumer_key || $this->settings->consumer_secret || $this->settings->access_token || $this->settings->access_secret || $this->settings->username);
    }
}