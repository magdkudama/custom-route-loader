<?php

namespace MagdKudama\CustomRouteBundle\Routing\BasicRest;

interface BasicRestLoaderInterface
{
    function getAllAction();

    function getOneAction($id);

    function postAction();

    function putAction($id);

    function deleteAction($id);
}
