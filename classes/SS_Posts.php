<?php

/**
 * Class to fetch news posts
 */
class SS_Posts extends SS_Provider
{
    /**
     * Pull posts from the database
     * @return self
     */
    public function fetch() {
        if(!$this->valid()) {
            $this->posts = array();
            return $this;
        }

        $args = array(
            'posts_per_page' => 15,
        );

        $this->posts = array();

        $q = new WP_Query($args);

        $this->posts = array_map(function($item) {
            return new SS_WordpressPost($item);
        }, $q->posts);

        return $this;
    }

    /**
     * Checks if the required settings have been set
     * @return self
     */
    private function valid() {
        return ($this->settings->posts_per_page);
    }
}