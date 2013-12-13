<?php

namespace MagdKudama\CustomRouteBundle\Controller;

use MagdKudama\CustomRouteBundle\Routing\BasicRest\BasicRestLoaderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller implements BasicRestLoaderInterface
{
    public function getAllAction()
    {
        return new Response("Getting all");
    }

    public function getOneAction($id)
    {
        return new Response("Getting {$id}");
    }

    public function postAction()
    {
        return new Response("Creating");
    }

    public function putAction($id)
    {
        return new Response("Updating {$id}");
    }

    public function deleteAction($id)
    {
        return new Response("Deleting {$id}");
    }
}
