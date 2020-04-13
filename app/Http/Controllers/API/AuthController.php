<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers\API;

use App\User;
use App\Models\Secman\Secgroup;
use App\Models\Secman\Secusergroup;
use App\Models\Secman\Secmenu;
use App\Models\Secman\Secmenugroup;
use Psr\Http\Message\ServerRequestInterface;
use \Laravel\Passport\Http\Controllers\AccessTokenController;

class AuthController extends AccessTokenController
{
    public function auth(ServerRequestInterface $request)
    {
            $tokenResponse = parent::issueToken($request);
            $token = $tokenResponse->getContent();

            // $tokenInfo will contain the usual Laravel Passort token response.
            $tokenInfo = json_decode($token, true);

            // Then we just add the user to the response before returning it.
            $username = $request->getParsedBody()['username'];
            $user = User::whereEmail($username)->first();
            $group_ids = Secusergroup::where('user_id', $user->id)->get();

            $access = array();
            $menus = array();
            foreach ($group_ids as $id)
            {
                $acc = Secgroup::where('id', $id->group_id)->first()->gname;
                array_push($access, $acc);

                $daftar_menu_id = Secmenugroup::where('group_id', $id->group_id)->get();

                foreach ($daftar_menu_id as $mn_id) {
                    $mn = Secmenu::where('id', $mn_id->menu_id)->get();

                    $menu = array();
                    foreach ($mn as $data) {
                        $menu["id"] = $data["id"];
                        $menu["title"] = $data["mname"];
                        $menu["desc"] = $data["mdesc"];
                        $menu["icon"] = $data["micon"];
                        $menu["page"] = $data["muri"];
                        $menu["parent"] = $data["mparentid"];

                        if($data["mparentid"] === NULL) {
                            $menu["root"] = true;
                        } else {
                            $menu["root"] = false;
                        }
                    }

                    array_push($menus, $menu);
                }
            }

            $menus = array_map("unserialize", array_unique(array_map("serialize", $menus)));

            //$access[$group_name] = "can do anything";
            // $access = array(
            //     $group_name => "Can Do Anything"
            // );
            $tokenInfo = collect($tokenInfo);
            $tokenInfo->put('user', $user);
            $tokenInfo->put('access', $access);
            $tokenInfo->put('menus', $menus);
            return $tokenInfo;
    }
}