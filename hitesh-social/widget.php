<?php

echo $args['before_widget'];
 
if (!empty($instance['title'])) {
    echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
}

echo '<ul class="links">';

foreach ($this->plugin->getSocialUrlsFromDatabase() as $slug => $url) {

    $name = $this->plugin->getSocialSiteBySlug($slug)['name'];
	
    $url_prefix = $this->plugin->getSocialSiteBySlug($slug)['url_prefix'];

    echo '<li class="'. $slug .'"><a href="'. $url_prefix . $url .'" alt="'. $name .'">'. $name .'</a></li>';
}

echo "</ul>";

echo $args['after_widget'];