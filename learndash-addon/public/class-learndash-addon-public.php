<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://https://codepixelzmedia.com/
 * @since      1.0.0
 *
 * @package    Learndash_Addon
 * @subpackage Learndash_Addon/public
 */
/**



 * The public-facing functionality of the plugin.



 *



 * Defines the plugin name, version, and two examples hooks for how to



 * enqueue the public-facing stylesheet and JavaScript.



 *



 * @package    Learndash_Addon



 * @subpackage Learndash_Addon/public



 * @author     Codepixelz Media <wordpress.enthusiast@gmail.com>



 */



class Learndash_Addon_Public

{







	/**



	 * The ID of this plugin.



	 *



	 * @since    1.0.0



	 * @access   private



	 * @var      string    $plugin_name    The ID of this plugin.



	 */



	private $plugin_name;







	/**



	 * The version of this plugin.



	 *



	 * @since    1.0.0



	 * @access   private



	 * @var      string    $version    The current version of this plugin.



	 */



	private $version;







	/**



	 * Initialize the class and set its properties.



	 *



	 * @since    1.0.0



	 * @param      string    $plugin_name       The name of the plugin.



	 * @param      string    $version    The version of this plugin.



	 */



	public function __construct($plugin_name, $version)

	{







		$this->plugin_name = $plugin_name;



		$this->version = $version;

	}







	/**



	 * Register the stylesheets for the public-facing side of the site.



	 *



	 * @since    1.0.0



	 */



	public function enqueue_styles()

	{







		/**



		 * This function is provided for demonstration purposes only.



		 *



		 * An instance of this class should be passed to the run() function



		 * defined in Learndash_Addon_Loader as all of the hooks are defined



		 * in that particular class.



		 *



		 * The Learndash_Addon_Loader will then create the relationship



		 * between the defined hooks and the functions defined in this



		 * class.



		 */







		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/learndash-addon-public.css', array(), $this->version, 'all');



		// wp_enqueue_style('load-fa', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css');
		wp_enqueue_style('load-fa', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');


	}







	/**



	 * Register the JavaScript for the public-facing side of the site.



	 *



	 * @since    1.0.0



	 */



	public function enqueue_scripts()

	{







		/**



		 * This function is provided for demonstration purposes only.



		 *



		 * An instance of this class should be passed to the run() function



		 * defined in Learndash_Addon_Loader as all of the hooks are defined



		 * in that particular class.



		 *



		 * The Learndash_Addon_Loader will then create the relationship



		 * between the defined hooks and the functions defined in this



		 * class.



		 */







		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/learndash-addon-public.js', array( 'jquery' ), time(), false );



		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/learndash-addon-public.js', array('jquery'), time(), false);



		wp_localize_script($this->plugin_name, 'myAjax', array('ajax_url' => esc_url(admin_url('admin-ajax.php'))));



		wp_localize_script($this->plugin_name, 'getUrl', array('homeUrl' => esc_url(home_url('/'))));

	}





	public function custom_question_tags_page_template($templates)

	{

		$templates[plugin_dir_path(__FILE__) . 'custom-question-tags-page.php'] = __('Question Tags Page Template', 'learndash-addon');

		$templates[plugin_dir_path(__FILE__) . 'custom-question-single-page.php'] = __('Question Single Page Template', 'learndash-addon');



		return $templates;

	}



	public function custom_question_tags_page_template_save_edit($template)

	{

		if (is_page()) {

			$meta = get_post_meta(get_the_ID());



			if (!empty($meta['_wp_page_template'][0]) && $meta['_wp_page_template'][0] != $template) {

				$template = $meta['_wp_page_template'][0];

			}

		}



		return $template;

	}

}
