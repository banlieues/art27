<?php

namespace Base\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class IdentificationCheckFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // $uri = new \CodeIgniter\HTTP\URI(current_url());
        // if($uri->getSegment(1)!="identification")
        // {
        //     session()->set(["url_prec"=>current_url()]);
        // }  

        if (empty(session('loggedUserId')))
        {
            $redirect = current_url();

            if(current_url()==base_url()) return redirect()->to(base_url("identification"));
            
            return redirect()->to(base_url("identification?redirect=$redirect"))->with('warning', "Vous devez d'abord vous connecter...");
            // return redirect()->back();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
