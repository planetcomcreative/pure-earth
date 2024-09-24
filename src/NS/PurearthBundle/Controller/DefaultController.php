<?php

namespace NS\PurearthBundle\Controller;

use NS\Purearth\Config\Exceptions\ConfigNotFoundException;
use NS\Purearth\Content\Exceptions\ContentNotFoundException;
use NS\Purearth\Content\Exceptions\ContentNotUniqueException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $wordpress = $this->get('nswordpressbundle.wordpress');
        $cart = $this->get('ns_purearth.cart_manager')->getCart();

        try
        {
            $featured = $this->get('ns.purearth.config')->get('featured_video');
            $postId = $wordpress->call('url_to_postid', $featured->getValue());
            $featuredPost = $wordpress->call('get_post', $postId);
            $featuredVideo = $wordpress->call('get_media_embedded_in_content', $wordpress->call('apply_filters', 'the_content', $featuredPost->post_content));
        }
        catch(ConfigNotFoundException $e)
        {
            $featuredPost = false;
            $featuredVideo = false;
        }

        $imgs = ['/bundles/nspurearth/images/homepage/1.jpg', '/bundles/nspurearth/images/homepage/2.jpg', '/bundles/nspurearth/images/homepage/3.jpg', '/bundles/nspurearth/images/homepage/4.jpg'];
        shuffle($imgs);
        return $this->render('NSPurearthBundle:Homepage:index.html.twig', array('imgs' => $imgs, 'cart'=>$cart, 'featuredPost'=>$featuredPost, 'featuredVideo'=>$featuredVideo));
    }

    /**
     * @Route("/about-us", name="about_us")
     */
    public function aboutAction(Request $request)
    {
        return $this->getContent('AboutUs');
    }

    /**
     * @Route("/store", name="store")
     */
    public function storeAction(Request $request)
    {
        return $this->getContent('Store');
    }

    /**
     * @Route("/contact-us", name="contact_us")
     */
    public function contactAction(Request $request)
    {
        return $this->getContent('ContactUs');
    }

    /**
     * @Route("/faqs", name="faqs")
     */
    public function faqsAction(Request $request)
    {
        return $this->getContent('FAQs');
    }

    /**
     * @Route("/links", name="links")
     */
    public function linksAction(Request $request)
    {
        return $this->getContent('Links');
    }

    /**
     * @Route("/juices", name="juices")
     */
    public function juicesAction(Request $request)
    {
        return $this->getContent('OurJuices');
    }

    /**
     * @Route("/info/{key}", name="static_page")
     */
    public function staticPageAction(Request $request, $key)
    {
        try
        {
            return $this->getContent($key);
        }
        catch(ContentNotFoundException $e)
        {
            throw $this->createNotFoundException('Sorry, page not found.');
        }

    }

    public function contentAction($slug, $template = 'NSPurearthBundle:Content:index.html.twig')
    {
        return $this->getContent($slug, $template);
    }

    protected function getContent($slug, $template = 'NSPurearthBundle:Content:index.html.twig')
    {
        try
        {
            $content = $this->get('ns.purearth.content')->get($slug);
        }
        catch(ContentNotFoundException $e)
        {
            throw $this->createNotFoundException('Sorry, page not found.');
        }
        catch(ContentNotUniqueException $e)
        {
            throw new \Exception('More than one Content object exists with this key');
        }

        return $this->render($template, ['content'=>$content]);
    }
}
