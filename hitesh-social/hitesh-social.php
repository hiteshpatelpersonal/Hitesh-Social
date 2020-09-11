<?php
/*
Plugin Name: Hitesh Social
Plugin URI: 
Description: Plugin for social icons.
Version: 1.0
Author: Hitesh Patel
Author URI: http://hitesh-patel.uk

 */

namespace hitesh {

class HiteshSocial {

  const PLUGIN_NAME = 'Hitesh Social';
  const PLUGIN_SLUG = 'hitesh-social';

  const OPTION_SOCIAL_URLS = HiteshSocial::PLUGIN_SLUG .'-social_urls';

  private $socialMediaSites = [];

  public function __construct() {
	  
    $this->addSocialMediaUrl('facebook', 'Facebook', 'https://www.facebook.com/');
    $this->addSocialMediaUrl('twitter', 'Twitter', 'https://twitter.com/');
    $this->addSocialMediaUrl('linkedin', 'LinkedIn', 'https://www.linkedin.com/in/');


    if (is_admin()) {
        add_action('admin_menu', array($this, 'setupAdminMenu'));
    } else {
        add_action('wp_enqueue_scripts', array($this, 'addStylesheet'));
    }

    add_action('widgets_init', function() {
		
      register_widget('\hitesh\HiteshSocialWidget');
      
      global $wp_widget_factory;
	  
      $widget = $wp_widget_factory->widgets['\hitesh\HiteshSocialWidget'];
      $widget->setPlugin($this);
	  
    });
  }

  public function addSocialMediaUrl($slug, $name, $urlPrefix) {
    $this->socialMediaSites[$slug] = array(
      'slug' => $slug,
      'name' => $name,
      'url_prefix' => $urlPrefix
    );
  }

  public function getSocialSiteBySlug($slug) {
    if (isset($this->socialMediaSites[$slug])) {
      return $this->socialMediaSites[$slug];
    } else {
      return null;
    }
  }

  public function setupAdminMenu() {
    add_menu_page(HiteshSocial::PLUGIN_NAME, HiteshSocial::PLUGIN_NAME, 'edit_plugins', HiteshSocial::PLUGIN_SLUG, array($this, 'handleSettingsPage'));
  }

  public function addStylesheet() {
    wp_enqueue_style(HiteshSocial::PLUGIN_NAME, plugins_url(HiteshSocial::PLUGIN_SLUG .'/css/style.css')); 
  }

  public function handleSettingsPage() {
	  
    if (isset($_POST['submitted'])) {
        $socialUrls = $this->getSocialUrlsFromPost();
        update_option(HiteshSocial::OPTION_SOCIAL_URLS, json_encode($socialUrls, JSON_UNESCAPED_UNICODE));        
        $settingsUpdated = true;
    } else {
      $socialUrls = $this->getSocialUrlsFromDatabase();
    }
    include('settings.php');
  }

  private function getSocialUrlsFromPost() {
	  
    $socialUrls = [];
    foreach ($this->socialMediaSites as $site) {
      $fieldValue = $_POST['social_urls-'. $site['slug']];
      if (!empty(trim($fieldValue))) {
        $socialUrls[$site['slug']] = $fieldValue;
		
      }
    }
    return $socialUrls;
  }

  public function getSocialUrlsFromDatabase() {
    return json_decode(get_option(HiteshSocial::OPTION_SOCIAL_URLS, []), true);
  }
}

class HiteshSocialWidget extends \WP_Widget {

    private $plugin;

    function __construct() {
        parent::__construct(
            HiteshSocial::PLUGIN_SLUG .'-social',  // Base ID
            HiteshSocial::PLUGIN_NAME .' Social'   // Name
        );
 
        $this->args['before_widget'] = '<div class="widget-wrap '. $this->base_id .'">'; 
    }

    public function setPlugin($plugin) {
      $this->plugin = $plugin;
    }
 
    public $args = array(
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="widgetwrap">',
        'after_widget'  => '</div></div>'
    );
 
    public function widget($args, $instance) {
        include('widget.php'); 
    }
 
    public function form($instance) {
 
        $title = ! empty($instance['title']) ? $instance['title'] : esc_html__('', 'text_domain');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'text_domain'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
 
    }
 
    public function update($new_instance, $old_instance) {
 
        $instance = array();
 
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
 
        return $instance;
    }
} 

$hiteshSocial = new HiteshSocial();
$hiteshSocialWidget = new HiteshSocialWidget();

}