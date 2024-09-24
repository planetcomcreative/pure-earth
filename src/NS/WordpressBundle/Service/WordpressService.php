<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 08/11/16
 * Time: 10:44 AM
 */

namespace NS\WordpressBundle\Service;

use NS\WordpressBundle\Exception\WordpressFunctionNotFoundException;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class WordpressService
{
    /** @var RouterInterface */
    protected $router;

    public function __construct($wp_path, RouterInterface $router)
    {
        if(is_file($wp_path.'wp-blog-header.php')) {
            $this->router = $router;
            require_once($wp_path.'wp-blog-header.php');
            require_once($wp_path.'wp-includes/rewrite.php');

            $this->addFilters();
        }
    }

    protected function addFilters()
    {
        add_filter('excerpt_more', [$this, 'new_excerpt_more']);
        add_filter('embed_oembed_html', [$this, 'video_embed_html'], 10, 3);
        add_filter('video_embed_html', [$this, 'video_embed_html']);
    }

    public function call($function)
    {
        if(function_exists($function))
        {
            global $post;
            $args = func_get_args();
            array_shift($args); //$args[0] is the function name

            foreach($args as $arg)
            {
                if($arg instanceof \WP_Post)
                {
                    $GLOBALS['post'] = $arg; //Ugh, Wordpress...
                }
            }

            return call_user_func_array($function, $args);
        }
        else
        {
            throw new WordpressFunctionNotFoundException('Wordpress function, "'.$function.'",  not found.');
        }
    }

    // TODO: Move this to its own class
    public function new_excerpt_more($more) {
        global $post;
        return '<a class="moretag" href="'. $this->router->generate('wp_view', ['slug'=>$post->post_name]) . '"> Continue Reading...</a>';
    }

    public function video_embed_html($html) {
        return '<div class="video-container">' . $html . '</div>';
    }
}
