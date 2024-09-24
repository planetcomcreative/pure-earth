<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 03/11/16
 * Time: 12:22 PM
 */

namespace NS\PurearthBundle\Controller;

use Ekino\WordpressBundle\Tests\Event\Subscriber\WP_QueryMock;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use WP_Query;

/**
 * Class BlogController
 * @package NS\PurearthBundle\Controller
 *
 * @Route("/blog")
 */
class BlogController extends Controller
{
    /**
     * @Route("/", name="wp_index")
     */
    public function indexAction(Request $request)
    {
        $wordpress = $this->get('nswordpressbundle.wordpress');

        $page = $request->query->get('page', 1);
        $posts_per_page = $this->getParameter('wp_posts_per_page');
        $offset = $posts_per_page*($page-1);

        $posts = $wordpress->call('get_posts', 'numberposts='.$posts_per_page.'&offset='.$offset.'&order=DESC&orderby=post_date');
        $count = $wordpress->call('wp_count_posts');
        $total_pages = ceil($count->publish/$posts_per_page);

        $context = [
            'posts'=>$posts,
            'total_pages'=>$total_pages,
            'page'=>$page
        ];

        return $this->render('NSPurearthBundle:Blog:index.html.twig', $context);
    }

    public function recentAction()
    {
        $wordpress = $this->get('nswordpressbundle.wordpress');

        $recent = $wordpress->call('wp_get_recent_posts', ['numberposts' => 5, 'post_status' => 'publish']);

        return $this->render('NSPurearthBundle:Blog:recent.html.twig', ['recent'=>$recent]);
    }

    /**
     * @Route("/login", name="wp_login")
     */
    public function loginAction()
    {
        return $this->redirect('/peblog/wp-login.php');
    }

    /**
     * @Route("/tag/{tag}", name="wp_tag")
     */
    public function tagAction(Request $request, $tag)
    {
        $this->get('nswordpressbundle.wordpress'); //We need to load this service so that the Wordpress dependencies get loaded for this request, even though we don't use it.  Blame Wordpress.

        $tag_query = new WP_Query('tag='.$tag);
        $tagged_posts = $tag_query->posts;

        return $this->render('NSPurearthBundle:Blog:tag.html.twig', ['tagged_posts'=>$tagged_posts, 'tag'=>$tag]);
    }

    /**
     * @Route("/search", name="wp_search")
     */
    public function searchAction(Request $request)
    {
        $this->get('nswordpressbundle.wordpress');

        $page = $request->query->get('page', 1);
        $posts_per_page = $this->getParameter('wp_posts_per_page');
        $offset = $posts_per_page*($page-1);

        $query = $request->query->get('query');
        $wp_query = new WP_Query('s='.$query);

        $posts = [];
        $total_pages = 0;

        if($wp_query->have_posts())
        {
            $posts = $wp_query->get_posts();
            $count = count($posts);
            $total_pages = ceil($count/$posts_per_page);
        }

        $context = [
            'posts'=>$posts,
            'total_pages'=>$total_pages,
            'page'=>$page,
            'query'=>$query
        ];

        return $this->render('NSPurearthBundle:Blog:search.html.twig', $context);
    }

    /**
     * @Route("/{slug}", name="wp_view")
     */
    public function viewAction(Request $request, $slug)
    {
        $wordpress = $this->get('nswordpressbundle.wordpress');

        $post = $wordpress->call('get_page_by_path', $slug, OBJECT, 'post');

        $next_post = $wordpress->call('get_next_post', false, '', 'category', $post);
        $previous_post = $wordpress->call('get_previous_post', false, '', 'category', $post);

        return $this->render('NSPurearthBundle:Blog:view.html.twig', [
            'post'=>$post,
            'next_post'=>$next_post,
            'previous_post' => $previous_post
        ]);
    }

    public function _indexAction()
    {
        $posts = get_posts('numberposts=10&order=DESC&orderby=post_date');
        $p_arr = [];
        foreach ($posts as $post)
        {
            setup_postdata($post);
            $p_arr[get_the_id()] = [
                'title' => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'date' => get_the_date(),
                'post' => $post,
                'thumbnail' => get_the_post_thumbnail(),
                'author' => get_the_author()
            ];
        }
    }
}