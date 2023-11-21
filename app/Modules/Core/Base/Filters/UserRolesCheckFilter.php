<?php

namespace Base\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class UserRolesCheckFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
       
        $uri = new \CodeIgniter\HTTP\URI(current_url());
      
        if($uri->getSegment(1)!="identification")
        {
            session()->set(["url_prec"=>current_url()]);
        }  
        
        if (!session()->has('loggedUserId'))
        {
            $redirect = current_url();
            
            return redirect()->to(base_url("identification?redirect=$redirect"))->with('warning', "Vous devez d'abord vous connecter...");
        }

        $role = isset($arguments[0]) ? (int)$arguments[0] : 0;

        if ($role == 1 && session()->get('loggedUserRoleId') > $role)
        {
            return redirect()->to(base_url('forbidden'));
        }

        if ($role == 2 && session()->get('loggedUserRoleId') > $role)
        {
            return redirect()->to(base_url('forbidden'));
        }

        if ($role == 3 && session()->get('loggedUserRoleId') > $role)
        {
            return redirect()->to(base_url('forbidden'));
        }

        if ($role == 4 && session()->get('loggedUserRoleId') > $role)
        {
            return redirect()->to(base_url('forbidden'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
