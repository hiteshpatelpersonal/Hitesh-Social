<?php

function renderSocialMediaRow($site, $socialUrls) {
  echo '
      <tr>
          <th scope="row"><label for="social_urls-'. $site['slug'] .'">'. $site['name'] .'</label></th>
          <td>
            <span class="site-prefix">'. $site['url_prefix'] .'</span><input name="social_urls-'. $site['slug'] .'" type="text" value="'. $socialUrls[$site['slug']] .'" />
          </td>
      </tr>';
}

?>
<div class="wrap hitesh-about-me-settings-page">

  <h1><?php echo hitesh\HiteshSocial::PLUGIN_NAME; ?></h1>

	<?php
	if ($settingsUpdated) {
		echo '<div class="updated"><p>Settings updated.</p></div>';
	}
	?>

  <form method="POST">
  
    <div>
	
      <h2>Social Media URLs</h2>
      <table class="form-table">
	  
      <?php
      foreach ($this->socialMediaSites as $site) {
        renderSocialMediaRow($site, $socialUrls);
      }
      ?>
	  
      </table>
	  
    </div>
    
    <p class="submit">
      <input type="submit" value="Save Changes" name="submitted" class="button button-primary button-large">
    </p>
	
  </form>

</div>