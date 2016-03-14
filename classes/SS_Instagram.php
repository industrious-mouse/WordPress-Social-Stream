<?php

/**
 * Class to fetch posts from Instagram
 */
class SS_Instagram {

    /**
     * Link to the cache file
     */
    const CACHE_FILE = '/../cache/instagram';

    /**
     * Posts fetched from Instagram
     * @var array
     */
    public $posts;

    /**
     * Reference to SS_Settings
     * @var object
     */
    private $settings;

    /**
     * Let the magic begin
     */
    public function __construct() {
        $this->settings = new SS_Settings();
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

        if(!$this->check_cache()) {

            $feed = json_decode(file_get_contents('https://www.instagram.com/' . $this->settings->instagram_username . '/media/'), true);

            if(!isset($feed['status']) || $feed['status'] !== 'ok') {
                $this->posts = array();
            } else {
                $this->posts = array_map(function($item){
                    return array(
                        'image'         => $item['images']['standard_resolution']['url'],
                        'content'       => $item['caption']['text'],
                        'permalink'     => $item['link'],
                        'created_time'  => $item['created_time']
                    );
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

    /**
     * Check if the cache date is less than now and if so load the posts from there
     * @return bool
     */
    private function check_cache() {
        $cache = json_decode(file_get_contents(__DIR__.static::CACHE_FILE), true);
        if(isset($cache['expires']) && $cache['expires'] > time()) {
            $this->posts = $cache['posts'];
            return true;
        }
        return false;
    }

    /**
     * Write to the cache
     * @param  array $posts Posts to cache
     * @return void
     */
    private function write_cache() {
        $data = array(
            'expires' => strtotime('+1 hours'),
            'posts'   => $this->posts,
        );

        file_put_contents(__DIR__.static::CACHE_FILE, json_encode($data));
    }

    /**
     * Parse the Instagram text with Regex to load links, @replies and hashtags
     * @param  string $content The original post content
     * @return string          The parsed post content
     */
    public static function parse($content) {
        $content = nl2br($content);
		$content = preg_replace('/(http:\/\/[a-z0-9\.\/]+)/i', '<a href="$1" target="_blank">$1</a>', $content);
		$content = preg_replace('/(https:\/\/[a-z0-9\.\/]+)/i', '<a href="$1" target="_blank">$1</a>', $content);
		$content = preg_replace('/( @|^@)(\w+)/', '<a rel="nofollow" href="http://www.instagram.com/$2" target="_blank" title="Follow $2 on Instagram">$1$2</a>', $content);
		$content = preg_replace('/( #|^#)(\w+)/', '<a rel="nofollow" href="https://instagram.com/explore/tags/$2" target="_blank" title="$2">$1$2</a>', $content);

        return $content;
    }

}
