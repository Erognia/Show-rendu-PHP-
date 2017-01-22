/*TVShow.php*/
/**
     * Get the paginated list of published articles
     *
     * @param int $page
     * @param int $maxperpage
     * @param string $sortby
     * @return Paginator
*/
    public function getList($page=1, $maxperpage=10)
    {
        $q = $this->_em->createQueryBuilder()
            ->select('series')
            ->from('AppBundle:TVShow','series')
        ;
 
        $q->setFirstResult(($page-1) * $maxperpage)
            ->setMaxResults($maxperpage);
 
        return new Paginator($q);
    }
    

/*DefaultController.php*/
/**
     * @Route("/shows", name="shows")
     * @Template()
*/  
    public function ListAction($page)
    {
        $maxArticles = $this->container->getParameter('max_articles_per_page');
        $articles_count = $this->getDoctrine()
                ->getRepository('AppBundle:TVShow')
                ->countPublishedTotal();
        $pagination = array(
            'page' => $page,
            'route' => 'series',
            'pages_count' => ceil($series / $maxArticles),
            'route_params' => array()
        );
 
         $articles = $this->getDoctrine()->getRepository('AppBundle:TVShow')
                ->getList($page, $maxArticles);
 
        return $this->render('AppBundle:TVShow:show.html.twig', array(
            'series' => $series,
            'pagination' => $pagination
        ));
    }

/*shows.html.twig*/
<!--{#% include 'AppBundle:TVShow:show.html.twig' %#}-->

