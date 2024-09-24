<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 07/11/16
 * Time: 12:22 PM
 */

namespace NS\WordpressBundle\Twig;


use NS\WordpressBundle\Exception\WordpressFunctionNotFoundException;

class NSWordpressExtension extends \Twig_Extension
{
    private $wp_service;

    private $allowedFunctions = [
        'get_posts',
        'setup_postdata',
        'get_the_id',
        'get_the_title',
        'get_the_excerpt',
        'get_the_date',
        'get_the_post_thumbnail',
        'get_the_author',
        'get_permalink',
        'url_to_postid',
        'wp_trim_excerpt',
        'get_the_content',
        'apply_filters',
        'get_post_field',
        'get_next_post',
        'get_previous_post',
        'wp_get_post_tags',
        'wp_count_posts',
        'wp_get_recent_posts',
        'get_post',
        'get_media_embedded_in_content',
        'get_search_form'
    ];

    public function __construct($wp_service, array $allowedFunctions = [])
    {
        $this->wp_service = $wp_service;

        $this->allowedFunctions = array_merge($this->allowedFunctions, $allowedFunctions);
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('wordpress', array($this, 'call_wp_function'))
        ];
    }

    public function call_wp_function($function)
    {
        if(in_array($function, $this->allowedFunctions))
        {
            $args = func_get_args();
            return call_user_func_array([$this->wp_service, 'call'], $args);
        }
        else
        {
            throw new WordpressFunctionNotFoundException('Wordpress function, "'.$function.'", not allowed.');
        }
    }

    public function getName()
    {
        return 'wordpress_function_extension';
    }
}