<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/shows", name="shows")
     * @Template()
     */
    public function showsAction()
    {
        $em = $this->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:TVShow');

        return [
            'shows' => $repo->findAll()
        ];
    }

     public function ListAction($page)
    {
        $maxArticles = $this->container->getParameter('max_articles_per_page');
        $articles_count = $this->getDoctrine()
                ->getRepository('SimaDemoBundle:Article')
                ->countPublishedTotal();
        $pagination = array(
            'page' => $page,
            'route' => 'article_list',
            'pages_count' => ceil($articles_count / $maxArticles),
            'route_params' => array()
        );
 
         $articles = $this->getDoctrine()->getRepository('SimaDemoBundle:Article')
                ->getList($page, $maxArticles);
 
        return $this->render('SimaDemoBundle:Article:list.html.twig', array(
            'articles' => $articles,
            'pagination' => $pagination
        ));
    }

    /**
     * @Route("/show/{id}", name="show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:TVShow');

        return [
            'show' => $repo->find($id)
        ];        
    }

    /**
     * @Route("/login", name="login")
     * @Template()
     */
    public function loginAction()
    {
        return [];
    }


    /**
    * @Route("/search", name="search")
    * @Template()
    */
    public function ActionSearch(){
        $request = Request::createFromGlobals();
        $postData = $request->request->get('SearchName');

        $em = $this->get("doctrine")->getManager();
        $repo = $em->getRepository('AppBundle:TVShow');
        $query = $repo->createQueryBuilder('show')
            ->where('show.name LIKE :name OR show.synopsis LIKE :synopsis')
            ->setParameters(array('name' => '%' . $postData . '%','synopsis' => '%' . $postData . '%'))
            ->getQuery();

            return [ 'shows' => $query->getResult()];
    }

     /**
     * @Route("/calendar", name="calendar")
     * @Template()
     */
    public function calendarAction()
    {
      $this->date = new \DateTime('now');
      $dateActuelle = $this->date->format('d/m/Y');
      $em = $this->get('doctrine')->getManager();
      $repo = $em->getRepository('AppBundle:Episode');
      // $query = $repo->createQueryBuilder('calendar')
      // ->where('calendar.date >='.$dateActuelle)
      // ->setParameters(array('name' => '%' . $postData . '%','synopsis' => '%' . $postData . '%'))
      // ->getQuery();
      //
      return ['episodes' => $repo->findAll(), 'dateActuelle' => $dateActuelle];
    }
}
